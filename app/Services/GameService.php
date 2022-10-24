<?php


namespace App\Services;


use App\Exception\InvalidColumn;
use App\Exception\InvalidRow;
use App\Exception\NotEmpty;
use App\Exception\UpdateError;
use App\Model\Game;

/**
 * Class GameService
 * @package App\Services
 */
class GameService
{
    /**
     * @param Game $game
     * @param int $row
     * @param int $column
     * @return Game
     * @throws InvalidColumn
     * @throws InvalidRow
     * @throws NotEmpty
     * @throws UpdateError
     */
    public function makeMove(Game $game, int $row, int $column): Game
    {
        $this->validate();
        $field = $game->getGameField();
        $this->isFieldFree($field, $row, $column);
        $field[$row - 1][$column - 1] = $game->getGamePlayer();
        $game->setGameField($field);
        $this->checkGameStatus($game);
        $nextPlayer = $this->getNextPlayer($game->getGamePlayer());
        $data = [
            'id' => $game->getGameId(),
            'state' => $game->getGameState(),
            'field' => json_encode($field),
            'player' => $nextPlayer,
            'winner' => $game->getGameWinner(),
        ];
        if ($game->updateGame($game->getGameId(), $data)) {
            return $game;
        } else {
            throw new UpdateError('Error while updating');
        }
    }

    /**
     * @param Game $game
     */
    public function checkGameStatus(Game $game): void
    {
        $player = $game->getGamePlayer();
        $field = $game->getGameField();
        for ($i = 0; $i < 3; $i++) {
            if ($field[$i][0] === $player &&
                $field[$i][1] === $player &&
                $field[$i][2] === $player
            ) {
                $game->setGameState(Game::CLOSE);
                $game->setGameWinner($player);
            }

            if ($field[0][$i] === $player &&
                $field[1][$i] === $player &&
                $field[2][$i] === $player
            ) {
                $game->setGameState(Game::CLOSE);
                $game->setGameWinner($player);
            }

            if ($field[0][1] === $player &&
                $field[1][2] === $player &&
                $field[2][2] === $player
            ) {
                $game->setGameState(Game::CLOSE);
                $game->setGameWinner($player);
            }

            if ($field[0][2] === $player &&
                $field[1][1] === $player &&
                $field[2][0] === $player
            ) {
                $game->setGameState(Game::CLOSE);
                $game->setGameWinner($player);
            }

            $count_tile_occupied = 0;
            foreach ($field as $array) {
                foreach ($array as $value) {
                    if ($value !== Game::EMPTY_FIELD) {
                        $count_tile_occupied++;
                    }
                }
            }

            if ($count_tile_occupied == 9) {
                $game->setGameState(Game::CLOSE);
                $game->setGameWinner(Game::DRAW);
            }
        }
    }

    /**
     * @param $field
     * @param $row
     * @param $column
     * @throws NotEmpty
     */
    public function isFieldFree($field, $row, $column): void
    {
        if ($field[$row - 1][$column - 1] !== Game::EMPTY_FIELD) {
            throw new NotEmpty(
                sprintf(
                    'The field is already taken, please select another one.'
                )
            );
        }
    }

    /**
     * @param $player
     * @return string
     */
    public function getNextPlayer($player): string
    {
        return $player = $player === 'X' ? 'O' : 'X';
    }

    /**
     * @throws InvalidRow|InvalidColumn
     */
    public function validate(): void
    {
        $fields = ['row', 'column'];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            foreach ($fields as $field) {
                if (empty($_POST[$field])) {
                    throw new InvalidRow(
                        sprintf(
                            'Missing %s.',
                            $field,
                        )
                    );
                }
                if ($_POST[$field] < 1 || $_POST[$field] > 3) {
                    if ($field === 'row') {
                        throw new InvalidRow(
                            sprintf(
                                '%s must be 1-3.',
                                $field
                            )
                        );
                    }
                    if ($field === 'column') {
                        throw new InvalidColumn(
                            sprintf(
                                '%s must be 1-3.',
                                $field
                            )
                        );
                    }
                }
            }
        }
    }
}