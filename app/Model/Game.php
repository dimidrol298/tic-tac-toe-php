<?php

namespace App\Model;


use App\Exception\NotFoundGame;

/**
 * Class Game
 * @package App\Model
 */
class Game extends DataBase
{
    const TABLE_NAME = 'games';
    const OPEN = 'open';
    const CLOSE = 'close';
    const DRAW = 'draw';
    const PLAYER_X = 'X';
    const EMPTY_FIELD = "";

    /**
     * @var int
     */
    private int $id;
    /**
     * @var array
     */
    private array $field;
    /**
     * @var string
     */
    private string $state;
    /**
     * @var string
     */
    private string $player;
    /**
     * @var string|null
     */
    private ?string $winner;
    /**
     * @var array
     */
    private array $boardCells;

    /**
     * Game constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->boardCells = [
            ["", "", ""],
            ["", "", ""],
            ["", "", ""]
        ];
    }

    /**
     * @param array $field
     */
    public function setGameField(array $field): void
    {
        $this->field = $field;
    }

    /**
     * @return array
     */
    public function getGameField(): array
    {
        return $this->field;
    }

    /**
     * @param string $state
     */
    public function setGameState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getGameState(): string
    {
        return $this->state;
    }

    /**
     * @param string $player
     */
    public function setPlayer(string $player): void
    {
        $this->player = $player;
    }

    /**
     * @return string
     */
    public function getGamePlayer(): string
    {
        return $this->player;
    }

    /**
     * @param int $id
     */
    public function setGameId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->id;
    }

    /**
     * @param null|string $winner
     */
    public function setGameWinner(?string $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return string|null
     */
    public function getGameWinner(): string|null
    {
        return $this->winner;
    }

    /**
     * @return array
     */
    public function getAllGames(): array
    {
        return $this->dbConn->from(self::TABLE_NAME)
            ->select()
            ->all();
    }

    /**
     * @param int $id
     * @return Game
     * @throws NotFoundGame
     */
    public function getGame(int $id): Game
    {
        $game = $this->dbConn->from(self::TABLE_NAME)
            ->where('id')->is($id)
            ->where('state')->is(self::OPEN)
            ->select()
            ->first();
        if ($game === false) {
            throw new NotFoundGame('Game not found');
        }

        $this->setGameId($game->id);
        $this->setGameField(json_decode($game->field));
        $this->setGameState($game->state);
        $this->setPlayer($game->player);
        $this->setGameWinner($game->winner);

        return $this;
    }

    /**
     * @return false|string
     */
    public function getGameTable(): false|string
    {
        return json_encode([
            'id' =>   $this->getGameId(),
            'field' => json_encode($this->getGameField()),
            'state' => $this->getGameState(),
            'player' => $this->getGamePlayer(),
            'winner' => $this->getGameWinner(),
        ], JSON_PRETTY_PRINT);
    }

    /**
     * @return false|string
     */
    public function createNewGame(): false|string
    {
        $this->setGameId($this->getLastId());
        $this->setGameField($this->boardCells);
        $this->setGameState(Game::OPEN);
        $this->setPlayer(Game::PLAYER_X);
        $this->setGameWinner(null);

        $data = [
            'id' => $this->getGameId(),
            'field' => json_encode($this->getGameField()),
            'state' => $this->getGameState(),
            'player' => $this->getGamePlayer(),
        ];

        $this->dbConn->insert($data)->into(self::TABLE_NAME);

        return $this->getGameTable();
    }

    /**
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateGame(int $id, array $data): int
    {
        return $this->dbConn->update(self::TABLE_NAME)
            ->where('id')->is($id)
            ->set($data);
    }

    /**
     * @param int $id
     * @return int
     */
    public function deleteGame(int $id): int
    {
        return $this->dbConn->from(self::TABLE_NAME)
            ->where('id')->is($id)
            ->delete();
    }

    /**
     * @return int
     */
    private function getLastId():int
    {
        $lastId = $this->dbConn->from(self::TABLE_NAME)
            ->orderBy('id', 'desc')
            ->select()
            ->first();
        if ($lastId) {
            return $lastId->id + 1;
        }

        return 1;
    }
}
