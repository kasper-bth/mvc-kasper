<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonController extends AbstractController
{
    #[Route("/api/lucky", name: "api_lucky")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", name: "api_quote")]
    public function jsonQuote(): Response
    {
        date_default_timezone_set('Europe/Stockholm');

        $quotes = [
            "Den som vill leva om sitt liv har inte levat. --Karen Blixen",
            "Hat dämpas aldrig av mer hat, det kan bara avväpnas genom vänskap och förståelse. --Buddha",
            "Jag har så enkel smak. Jag vill bara ha det bästa av allt. --Oscar Wilde"
        ];

        $randomIndex = array_rand($quotes);
        $randomQuote = $quotes[$randomIndex];

        $data = [
            'quotes' => $randomQuote,
            'date' => date('d-m-Y'),
            'time' => date('H:i:s')
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
