<?php

namespace App\Proj\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjPlayerController extends AbstractController
{
    #[Route("/proj/config", name: "proj_config")]
    public function config(SessionInterface $session): Response
    {
        $player = $session->get('blackjack_player');
        if (!$player) {
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
        $game = $session->get('blackjack_game');
        $player = $session->get('blackjack_player');
        
        if ($game && $player) {
            $numHands = (int)$request->request->get('hands');
            $betAmount = (int)$request->request->get('bet');
            
            $currentHands = count($game->getPlayerHands());
            if ($numHands > $currentHands) {
                for ($i = $currentHands; $i < $numHands; $i++) {
                    $game->addHand();
                }
            }
            
            if (!$player->updateBet($betAmount)) {
                $this->addFlash('error', 'Ogiltig insats');
            }
            
            $session->set('blackjack_game', $game);
            $session->set('blackjack_player', $player);
        }
        
        return $this->redirectToRoute('proj_game');
    }
}