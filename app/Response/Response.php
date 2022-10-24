<?php

namespace App\Response;


use App\Model\Game;

/**
 * Class Response
 * @package App\Response
 */
class Response implements ResponseBodyInterface
{
    /**
     * @param Game $game
     * @return string
     */
    public function toJson(Game $game): string
    {
        if ($game->getGameWinner()) {
            return $game->getGameTable();
        }

        $response = [
            'data'   => [
                'id'              => $game->getGameId(),
                'field'           => $game->getGameField(),
                'state'           => $game->getGameState(),
                'next_player'     => $game->getGamePlayer(),
            ],
        ];

        return json_encode($response, JSON_PRETTY_PRINT);
    }
}