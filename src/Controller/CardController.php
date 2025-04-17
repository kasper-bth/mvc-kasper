<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function deck(SessionInterface $session): Response
    {
        $deck = $session->get('deck') ?? new DeckOfCards();

        $sortedCards = $deck->getSortedCards();

        $sortedCardStrings = array_map(function ($card) {
            return $card->getAsString();
        }, $sortedCards);

        return $this->render('card/deck.html.twig', [
            'deck' => $sortedCardStrings,
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $session->set('deck', $deck);

        return $this->render('card/shuffle.html.twig', [
            'deck' => $deck->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(
        SessionInterface $session
    ): Response {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $card = $deck->drawCard();
        $session->set('deck', $deck);

        return $this->render('card/draw.html.twig', [
            'card' => $card ? $card->getAsString() : null,
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_number")]
    public function drawNumber(
        int $number,
        SessionInterface $session
    ): Response {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $hand = new CardHand();

        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $hand->addCard($card);
            }
        }

        $session->set('deck', $deck);
        $session->set('hand', $hand);

        return $this->render('card/draw_number.html.twig', [
            'cards' => $hand->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/session", name: "card_session")]
    public function session(SessionInterface $session): Response
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $hand = $session->get('hand');
    
        $sessionData = [
            'deck' => $deck ? $deck->getString() : null,
            'hand' => $hand ? $hand->getString() : null,
            'remaining_cards' => $deck ? $deck->getNumberCards() : 0
        ];
    
        return $this->render('card/session.html.twig', [
            'session_data' => $sessionData
        ]);
    }

    #[Route("/session/delete", name: "card_session_delete")]
    public function deleteSession(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('notice', 'Session has been cleared successfully!');
        return $this->redirectToRoute('card_home');
    }
}
