<?php

namespace App\Proj\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProjHomeController extends AbstractController
{
    #[Route("/proj", name: "proj_home")]
    public function home(SessionInterface $session): Response
    {
        $currentPlayer = $session->get('current_player');
        $serializedPlayers = $session->get('blackjack_players', []);
        
        $players = [];
        foreach ($serializedPlayers as $nickname => $serializedPlayer) {
            $players[$nickname] = unserialize($serializedPlayer);
        }
        
        return $this->render('proj/home.html.twig', [
            'current_player' => $currentPlayer,
            'players' => $players
        ]);
    }

    #[Route("/proj/about", name: "proj_about")]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }
}