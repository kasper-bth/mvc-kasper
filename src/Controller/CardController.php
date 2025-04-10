<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\DeckOfCards;
use App\Card\CardHand;

class CardController extends AbstractController
{
    #[Route("/card", name: "card_home")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(): Response
    {
        $deck = new DeckOfCards();
        
        return $this->render('card/deck.html.twig', [
            'deck' => $deck->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffle(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        
        return $this->render('card/shuffle.html.twig', [
            'deck' => $deck->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $card = $deck->drawCard();
        
        return $this->render('card/draw.html.twig', [
            'card' => $card ? $card->getAsString() : null,
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_number")]
    public function drawNumber(int $number): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $hand = new CardHand();
        
        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $hand->addCard($card);
            }
        }
        
        return $this->render('card/draw_number.html.twig', [
            'cards' => $hand->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }
}
