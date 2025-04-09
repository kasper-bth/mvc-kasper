<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JsonController
{
    #[Route("/api/lucky")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        date_default_timezone_set('Europe/Stockholm');

        $quotes = ["Den som vill leva om sitt liv har inte levat. --Karen Blixen",
        "Hat dämpas aldrig av mer hat, det kan bara avväpnas genom vänskap och förståelse. --Buddha",
        "Jag har så enkel smak. Jag vill bara ha det bästa av allt. --Oscar Wilde"];

        $randomIndex = array_rand($quotes);

        $randomQuote = $quotes[$randomIndex];

        $data = [
            'quotes' => $randomQuote,
            'date' => date('d-m-Y'),
            'time' => date('H:i:s')
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
