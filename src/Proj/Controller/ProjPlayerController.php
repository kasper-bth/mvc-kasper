<?php

namespace App\Proj\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Proj\ProjDeck;
use App\Proj\ProjGame;

class ProjPlayerController extends AbstractController
{
    #[Route("/proj/config", name: "proj_config")]
    public function config(SessionInterface $session): Response
    {
        $player = $session->get('blackjack_player');
        if (!$player) {
            return $this->redirectToRoute('proj_home');
        }

        if ($player->getBankroll() <= 0) {
            $this->addFlash('error', 'Din bankroll är tom, starta ett nytt spel.');
            return $this->redirectToRoute('proj_home');
        }

        return $this->render('proj/config.html.twig', [
            'player' => $player,
            'max_hands' => 3
        ]);
    }

    #[Route("/proj/update-config", name: "proj_update_config", methods: ['POST'])]
    public function updateConfig(Request $request, SessionInterface $session): Response
    {
        $player = $session->get('blackjack_player');

        if (!$player) {
            return $this->redirectToRoute('proj_home');
        }

        $numHands = (int)$request->request->get('hands', 1);
        $betAmount = (int)$request->request->get('bet', 10);
        $totalBet = $betAmount * $numHands;

        if ($totalBet > $player->getBankroll()) {
            $this->addFlash('error', 'Ogiltig insats - för låg bankroll');
            return $this->redirectToRoute('proj_config');
        }

        $player->clearBets();

        $deck = new ProjDeck();
        $newGame = new ProjGame($deck, $player);

        for ($i = 0; $i < $numHands; $i++) {
            if ($i > 0) {
                $newGame->addHand();
            }
            if (!$player->placeBet($betAmount, $i)) {
                $this->addFlash('error', 'Ogiltig insats');
                return $this->redirectToRoute('proj_config');
            }
        }

        $players = $session->get('blackjack_players', []);
        $players[$player->getNickname()] = serialize($player);
        $session->set('blackjack_players', $players);
        $session->set('blackjack_game', $newGame);
        $session->set('blackjack_player', $player);

        return $this->redirectToRoute('proj_game');
    }
}
