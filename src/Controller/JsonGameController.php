<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class JsonGameController extends AbstractController
{
    #[Route("/api/game", name: "api_game", methods: ['GET'])]
    public function jsonGameScore(SessionInterface $session): Response
    {
        $game = $session->get('game');

        if (!$game) {
            return new JsonResponse([
                'error' => 'No game in progress',
                'message' => 'Start a new game first'
            ], 404);
        }

        $response = new JsonResponse([
            'player_score' => $game->getPlayerScore(),
            'bank_score' => $game->getBankScore()
        ]);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
