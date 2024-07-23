<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalculatriceController extends AbstractController
{
    #[Route('/', name: 'app_calculatrice')]
    public function index(Request $request): Response
    {
        $resultat = null;
        if ($request->isMethod('POST')) {
            $nombre1 = floatval($request->request->get('nombre1'));
            $nombre2 = floatval($request->request->get('nombre2'));
            $operation = $request->request->get('operation');

            if (!is_numeric($nombre1) || !is_numeric($nombre2)) {
                $this->addFlash('error', 'Les valeurs fournies doivent être des nombres.');
            } else {
                $resultat = $this->effectuerOperation($nombre1, $nombre2, $operation);

                if ($resultat !== null) {
                    $this->addFlash('resultat', (string)$resultat);
                }
            }

            return $this->redirectToRoute('app_calculatrice');
        }

        return $this->render('calculatrice/index.html.twig', [
            'resultat' => $resultat
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
                if ($nombre2 != 0) {
                    return $nombre1 / $nombre2;
                } else {
                    $this->addFlash('error', 'Erreur : Division par zéro');
                    return null;
                }
            default:
                $this->addFlash('error', 'Opération non reconnue.');
                return null;
        }
    }
}
