<?php

use App\Controllers\GameController;
use Pecee\SimpleRouter\SimpleRouter;

SimpleRouter::group(['prefix' => '/api'], function () {
    SimpleRouter::get('all-games', [GameController::class, 'getAll']);
    SimpleRouter::get('game/{id}', [GameController::class, 'getGame']);
    SimpleRouter::post('start', [GameController::class, 'create']);
    SimpleRouter::post('game/{id}/move', [GameController::class, 'move']);
    SimpleRouter::delete('game/{id}/delete', [GameController::class, 'delete']);
});
