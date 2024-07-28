<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatriceController extends AbstractController
{
    #[Route('/', name: 'app_calculatrice')]
    public function index(Request $request, LoggerInterface $logger): Response
    {
        $resultat = null;
        if ($request->isMethod('POST')) {
            $nombre1   = floatval($request->request->get('nombre1'));
            $nombre2   = floatval($request->request->get('nombre2'));
            $operation = $request->request->get('operation');

            if (! is_numeric($nombre1) || ! is_numeric($nombre2)) {
                $this->addFlash('error', 'Les valeurs fournies doivent être des nombres.');
            } else {
                $resultat = $this->effectuerOperation($nombre1, $nombre2, $operation, $logger);
                $this->logAction($logger, 'calculate', [
                    'nombre1'   => $nombre1,
                    'nombre2'   => $nombre2,
                    'operation' => $operation,
                    'resultat'  => $resultat,
                ]);

                if (null !== $resultat) {
                    $this->addFlash('resultat', (string) $resultat);
                }
            }

            return $this->redirectToRoute('app_calculatrice');
        }

        return $this->render('calculatrice/index.html.twig', [
            'resultat' => $resultat,
        ]);
    }

    private function effectuerOperation(float $nombre1, float $nombre2, string $operation): ?float
    {
        switch ($operation) {
            case 'addition':
                return $nombre1 + $nombre2;
            case 'soustraction':
                return $nombre1 - $nombre2;
            case 'multiplication':
                return $nombre1 * $nombre2;
            case 'division':
                if (0 != $nombre2) {
                    return $nombre1 / $nombre2;
                } else {
                    $this->addFlash('error', 'Erreur : Division par zéro');

                    return null;
                }
                // no break
            default:
                $this->addFlash('error', 'Opération non reconnue.');

                return null;
        }
    }

    private function logAction(LoggerInterface $logger, string $action, array $data): void
    {
        $logEntry = [
            'timestamp' => (new \DateTime())->format('Y-m-d\TH:i:s.uP'),
            'action'    => $action,
            'data'      => $data,
        ];

        $logger->info(json_encode($logEntry), ['channel' => 'app']);
    }
}
