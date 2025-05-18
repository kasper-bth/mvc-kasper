<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\DeckOfCards;
use App\Card\CardHand;

class CardController extends AbstractController
{
    private function getDeckFromSession(SessionInterface $session): DeckOfCards
    {
        return $session->get('deck') ?? new DeckOfCards();
    }

    private function saveDeckToSession(SessionInterface $session, DeckOfCards $deck): void
    {
        $session->set('deck', $deck);
    }

    #[Route("/card", name: "card_home")]
    public function home(): Response
    {
        return $this->render('card/home.html.twig');
    }

    #[Route("/card/deck", name: "card_deck")]
    public function deck(SessionInterface $session): Response
    {
        $deck = $this->getDeckFromSession($session);
        
        return $this->render('card/deck.html.twig', [
            'deck' => array_map(fn($card) => $card->getAsString(), $deck->getSortedCards()),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/shuffle", name: "card_deck_shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $this->saveDeckToSession($session, $deck);

        return $this->render('card/shuffle.html.twig', [
            'deck' => $deck->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw", name: "card_deck_draw")]
    public function draw(SessionInterface $session): Response
    {
        $deck = $this->getDeckFromSession($session);
        $card = $deck->drawCard();
        $this->saveDeckToSession($session, $deck);

        return $this->render('card/draw.html.twig', [
            'card' => $card?->getAsString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/card/deck/draw/{number<\d+>}", name: "card_deck_draw_number")]
    public function drawNumber(int $number, SessionInterface $session): Response
    {
        $deck = $this->getDeckFromSession($session);
        $hand = new CardHand();

        for ($i = 0; $i < $number && ($card = $deck->drawCard()); $i++) {
            $hand->addCard($card);
        }

        $this->saveDeckToSession($session, $deck);
        $session->set('hand', $hand);

        return $this->render('card/draw_number.html.twig', [
            'cards' => $hand->getString(),
            'count' => $deck->getNumberCards()
        ]);
    }

    #[Route("/session", name: "card_session")]
    public function session(SessionInterface $session): Response
    {
        $deck = $this->getDeckFromSession($session);
        $hand = $session->get('hand');

        return $this->render('card/session.html.twig', [
            'session_data' => [
                'deck' => $deck->getString(),
                'hand' => $hand?->getString(),
                'remaining_cards' => $deck->getNumberCards()
            ]
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