import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const logToServer = async (url, logEntry) => {
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(logEntry),
      });
      if (!response.ok) {
        console.error('Failed to log entry:', response.statusText);
      }
    } catch (error) {
      console.error('Error logging entry:', error);
    }
  };
  
  const calculate = async (expression) => {
    try {
      const response = await fetch('http://localhost:8000/api/calculate', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ expression }),
      });
      const data = await response.json();
      if (response.ok) {
        console.log('Result:', data.result);
      } else {
        console.error('Error:', data.error);
      }
    } catch (error) {
      console.error('Error calculating:', error);
    }
  };
  
  // Example usage
  calculate('1+1');
  logToServer('http://localhost:8000/api/log', { action: 'button_click', target: '1' });