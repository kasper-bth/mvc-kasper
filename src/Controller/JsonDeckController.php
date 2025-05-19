<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class JsonDeckController extends AbstractController
{
    #[Route("/api/deck", name: "api_deck", methods: ['GET'])]
    public function jsonDeck(SessionInterface $session): Response
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $sortedCards = $deck->getSortedCards();

        $cardStrings = array_map(function ($card) {
            return $card->getAsString();
        }, $sortedCards);

        $data = [
            'deck' => $cardStrings,
            'count' => count($cardStrings)
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "api_deck_shuffle", methods: ['GET', 'POST'])]
    public function jsonShuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();

        $session->set('deck', $deck);

        $cardStrings = array_map(function ($card) {
            return $card->getAsString();
        }, $deck->getCards());

        $data = [
            'deck' => $cardStrings,
            'count' => count($cardStrings),
            'message' => 'Deck has been shuffled'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/draw", name: "api_deck_draw", methods: ['GET', 'POST'])]
    public function jsonDraw(SessionInterface $session): Response
    {
        return $this->drawCards($session, 1);
    }

    #[Route("/api/deck/draw/{number<\d+>}", name: "api_deck_draw_number", methods: ['GET', 'POST'])]
    public function jsonDrawNumber(SessionInterface $session, int $number): Response
    {
        return $this->drawCards($session, $number);
    }

    private function drawCards(SessionInterface $session, int $number): Response
    {
        $deck = $session->get('deck');

        $drawnCards = [];
        for ($i = 0; $i < $number; $i++) {
            $card = $deck->drawCard();
            if ($card) {
                $drawnCards[] = $card->getAsString();
            }
        }

        $session->set('deck', $deck);

        $data = [
            'drawn_cards' => $drawnCards,
            'remaining_cards' => $deck->getNumberCards(),
            'message' => count($drawnCards) . ' card(s) drawn'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
