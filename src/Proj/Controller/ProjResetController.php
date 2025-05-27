<?php

namespace App\Proj\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjResetController extends AbstractController
{
    #[Route("/proj/reset", name: "proj_reset")]
    public function reset(SessionInterface $session): Response
    {
        $nickname = $session->get('current_player');
        if ($nickname) {
            $players = $session->get('blackjack_players', []);
            $game = $session->get('blackjack_game');

            $shouldSavePlayer = $game && $game->getPlayer()->getBankroll() > 0;
            $players[$nickname] = $shouldSavePlayer ? serialize($game->getPlayer()) : null;
            $players = array_filter($players);

            $session->set('blackjack_players', $players);
        }

        $session->remove('blackjack_game');
        $session->remove('blackjack_player');
        $session->remove('current_player');

        return $this->redirectToRoute('proj_home');
    }

    #[Route("/proj/full-reset", name: "proj_full_reset")]
    public function fullReset(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('proj_home');
    }
}
