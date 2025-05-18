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
    private function getGameFromSession(SessionInterface $session): CardGame
    {
        return $session->get('game') ?? new CardGame(new DeckOfCards());
    }

    private function getDeckFromSession(SessionInterface $session): DeckOfCards
    {
        return $session->get('deck') ?? new DeckOfCards();
    }

    private function saveGameState(SessionInterface $session, CardGame $game, DeckOfCards $deck): void
    {
        $session->set('game', $game);
        $session->set('deck', $deck);
    }

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
        $game = $this->getGameFromSession($session);
        $deck = $this->getDeckFromSession($session);
        $this->saveGameState($session, $game, $deck);

        return $this->render('game/play.html.twig', $this->getGameViewData($game, $deck));
    }

    #[Route("/game/draw", name: "game_draw", methods: ['POST'])]
    public function gameDraw(SessionInterface $session): Response
    {
        $game = $this->getGameFromSession($session);
        $deck = $this->getDeckFromSession($session);
        
        $game->playerDraw($deck);
        $this->saveGameState($session, $game, $deck);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/stop", name: "game_stop", methods: ['POST'])]
    public function gameStop(SessionInterface $session): Response
    {
        $game = $this->getGameFromSession($session);
        $deck = $this->getDeckFromSession($session);
        
        $game->playerStop($deck);
        $this->saveGameState($session, $game, $deck);

        return $this->redirectToRoute('game_play');
    }

    #[Route("/game/reset", name: "game_reset", methods: ['POST'])]
    public function gameReset(SessionInterface $session): Response
    {
        $session->remove('game');
        $this->addFlash('notice', 'Game has been reset!');
        return $this->redirectToRoute('game_play');
    }

    private function getGameViewData(CardGame $game, DeckOfCards $deck): array
    {
        return [
            'player_hand' => $game->getPlayerHand(),
            'bank_hand' => $game->getBankHand(),
            'player_score' => $game->getPlayerScore(),
            'bank_score' => $game->getBankScore(),
            'game_over' => $game->getGameOver(),
            'winner' => $game->getGameOver() ? $game->getWinner() : null,
            'remaining_cards' => $deck->getNumberCards()
        ];
    }
}