<?php

namespace App\Proj\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Proj\ProjDeck;
use App\Proj\ProjGame;
use App\Proj\Player;

class ProjGameController extends AbstractController
{
    #[Route("/proj/game", name: "proj_game")]
    public function game(SessionInterface $session): Response
    {
        $game = $session->get('blackjack_game');
        $player = $session->get('blackjack_player');

        if (!$game || !$player) {
            return $this->redirectToRoute('proj_home');
        }

        return $this->render('proj/game.html.twig', [
            'game' => $game,
            'player' => $player
        ]);
    }

    #[Route("/proj/init", name: "proj_init", methods: ['POST'])]
    public function initGame(Request $request, SessionInterface $session): Response
    {
        $nickname = $request->request->get('nickname');
        $numHands = (int)$request->request->get('hands', 1);
        $betAmount = (int)$request->request->get('bet', 10);

        $players = $session->get('blackjack_players', []);
        $session->remove('blackjack_game');
        $session->remove('blackjack_player');

        $player = array_key_exists($nickname, $players)
            ? unserialize($players[$nickname])
            : new Player($nickname);

        $deck = new ProjDeck();
        $game = new ProjGame($deck, $player);

        for ($i = 0; $i < $numHands; $i++) {
            if ($i > 0) {
                $game->addHand();
            }
            if (!$player->placeBet($betAmount, $i)) {
                $this->addFlash('error', 'Ogiltig insats');
                return $this->redirectToRoute('proj_home');
            }
        }

        $this->saveGameState($session, $game, $player, $nickname);
        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/draw", name: "proj_draw")]
    public function draw(SessionInterface $session): Response
    {
        $game = $session->get('blackjack_game');
        if ($game) {
            $game->playerDraw();
            $session->set('blackjack_game', $game);
        }
        return $this->redirectToRoute('proj_game');
    }

    #[Route("/proj/stand", name: "proj_stand")]
    public function stand(SessionInterface $session): Response
    {
        $game = $session->get('blackjack_game');
        if ($game) {
            $game->playerStop();
            $this->saveGameState($session, $game, $game->getPlayer(), $session->get('current_player'));

            if ($game->getGameOver() && $game->getPlayer()->getBankroll() <= 0) {
                $this->addFlash('error', 'Din bankroll är tom, starta ett nytt spel.');
            }
        }
        return $this->redirectToRoute('proj_game');
    }
    private function saveGameState(SessionInterface $session, ProjGame $game, Player $player, string $nickname): void
    {
        $players = $session->get('blackjack_players', []);

        $players[$nickname] = serialize($player);

        $session->set('blackjack_players', $players);
        $session->set('blackjack_game', $game);
        $session->set('blackjack_player', $player);
        $session->set('current_player', $nickname);
    }

    #[Route("/proj/new-round", name: "proj_new_round", methods: ['POST'])]
    public function newRound(Request $request, SessionInterface $session): Response
    {
        $player = $session->get('blackjack_player');
        if (!$player) {
            return $this->redirectToRoute('proj_home');
        }

        if ($player->getBankroll() <= 0) {
            $this->addFlash('error', 'Din bankroll är tom, starta ett nytt spel.');
            return $this->redirectToRoute('proj_home');
        }

        $numHands = (int)$request->request->get('hands', 1);
        $betAmount = (int)$request->request->get('bet', 10);
        $totalBet = $betAmount * $numHands;

        $player->clearBets();

        if ($totalBet > $player->getBankroll()) {
            $this->addFlash('error', 'Ogiltig insats - för låg bankroll');
            return $this->redirectToRoute('proj_config');
        }

        $deck = new ProjDeck();
        $game = new ProjGame($deck, $player);

        for ($i = 0; $i < $numHands; $i++) {
            if ($i > 0) {
                $game->addHand();
            }
            if (!$player->placeBet($betAmount, $i)) {
                $player->clearBets();
                $this->addFlash('error', 'Ogiltig insats');
                return $this->redirectToRoute('proj_config');
            }
        }

        $session->set('blackjack_player', $player);
        $session->set('blackjack_game', $game);
        $session->save();

        return $this->redirectToRoute('proj_game');
    }
}
