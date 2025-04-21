<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;
use App\Card\CardGame;

class CardGameController extends AbstractController
{
    #[Route("/game", name: "game_home")]
    public function home(): Response
    {
        return $this->render('game/home.html.twig');
    }

    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/play", name: "game_play", methods: ['GET'])]
    public function gamePlay(SessionInterface $session): Response
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $game = $session->get('game') ?? new CardGame($deck);
        
        $session->set('deck', $deck);
        $session->set('game', $game);

        return $this->render('game/play.html.twig', [
            'player_hand' => $game->getPlayerHand(),
            'bank_hand' => $game->getBankHand(),
            'player_score' => $game->getPlayerScore(),
            'bank_score' => $game->getBankScore(),
            'game_over' => $game->getGameOver(),
            'winner' => $game->getGameOver() ? $game->getWinner() : null,
            'remaining_cards' => $deck->getNumberCards()
        ]);
    }

    #[Route("/game/draw", name: "game_draw", methods: ['POST'])]
    public function gameDraw(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        $game = $session->get('game');
        
        if ($deck && $game) {
            $game->playerDraw($deck);
            $session->set('deck', $deck);
            $session->set('game', $game);
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/stop", name: "game_stop", methods: ['POST'])]
    public function gameStop(SessionInterface $session): Response
    {
        $deck = $session->get('deck');
        $game = $session->get('game');
        
        if ($deck && $game) {
            $game->playerStop($deck);
            $session->set('deck', $deck);
            $session->set('game', $game);
        }

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/reset", name: "game_reset", methods: ['POST'])]
    public function gameReset(SessionInterface $session): Response
    {
        $session->remove('game');
        $this->addFlash('notice', 'Game has been reset!');
        return $this->redirectToRoute('game_play');
    }
}