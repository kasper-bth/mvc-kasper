<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Card\DeckOfCards;

class JsonController
{
    #[Route("/api/lucky", name: "api_lucky")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/quote", name: "api_quote")]
    public function jsonQuote(): Response
    {
        date_default_timezone_set('Europe/Stockholm');

        $quotes = [
            "Den som vill leva om sitt liv har inte levat. --Karen Blixen",
            "Hat dämpas aldrig av mer hat, det kan bara avväpnas genom vänskap och förståelse. --Buddha",
            "Jag har så enkel smak. Jag vill bara ha det bästa av allt. --Oscar Wilde"
        ];

        $randomIndex = array_rand($quotes);
        $randomQuote = $quotes[$randomIndex];

        $data = [
            'quotes' => $randomQuote,
            'date' => date('d-m-Y'),
            'time' => date('H:i:s')
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

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

    #[Route("/api/game", name: "api_game", methods: ['GET'])]
    public function jsonGameScore(SessionInterface $session): Response
    {
        $game = $session->get('game');

        if (!$game) {
            return new JsonResponse([
                'error' => 'No game in progress',
                'message' => 'Start a new game first'
            ], 404);
        }

        $response = new JsonResponse([
            'player_score' => $game->getPlayerScore(),
            'bank_score' => $game->getBankScore()
        ]);

        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
