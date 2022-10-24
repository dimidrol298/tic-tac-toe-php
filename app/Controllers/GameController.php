<?php

namespace App\Controllers;


use App\Exception\NotEmpty;
use App\Exception\InvalidColumn;
use App\Exception\InvalidRow;
use App\Exception\NotFoundGame;
use App\Exception\UpdateError;
use App\Model\Game;
use App\Response\Error;
use App\Response\Response;
use App\Services\GameService;

/**
 * Class GameController
 * @package App\Controllers
 */
class GameController
{
    /**
     * @var Game
     */
    private Game $game;
    /**
     * @var GameService
     */
    private GameService $gameService;
    /**
     * @var Error
     */
    private Error $error;
    /**
     * @var Response
     */
    private Response $response;

    /**
     * GameController constructor.
     */
    public function __construct()
    {
        $this->game = New Game();
        $this->gameService = new GameService();
        $this->error = new Error();
        $this->response = new Response();
    }

    /**
     * @return string
     */
    public function getAll(): string
    {
        return json_encode($this->game->getAllGames(), JSON_PRETTY_PRINT);
    }

    /**
     * @param int $id
     * @return string
     */
    public function getGame(int $id): string
    {
        try {
            $game = $this->game->getGame($id);
        } catch (NotFoundGame $e) {
            return $this->error->toJson($e->getMessage());
        }

        return $game->getGameTable();
    }

    /**
     * @return string
     */
    public function create(): string
    {
        return $this->game->createNewGame();
    }

    /**
     * @param int $id
     * @return string
     */
    public function move(int $id): string
    {
        if (!isset($_POST['row'])) {
            return 'Row is empty';
        }
        if (!isset($_POST['column'])) {
            return 'Column is empty';
        }
        try {
            $game = $this->game->getGame($id);
            $row = $_POST['row'];
            $column = $_POST['column'];
            $game = $this->gameService->makeMove($game, $row, $column);
        }
        catch (NotEmpty $e) {
            return $this->error->toJson($e->getMessage());
        } catch (InvalidColumn $e) {
            return $this->error->toJson($e->getMessage());
        } catch (InvalidRow $e) {
            return $this->error->toJson($e->getMessage());
        }  catch (NotFoundGame $e) {
            return $this->error->toJson($e->getMessage());
        } catch (UpdateError $e) {
            return $this->error->toJson($e->getMessage());
        }

        return $this->response->toJson($game);
    }

    /**
     * @param int $id
     * @return string
     */
    public function delete(int $id): string
    {
       return $this->game->deleteGame($id);
    }
}