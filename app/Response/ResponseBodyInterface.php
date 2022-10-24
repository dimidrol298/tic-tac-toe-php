<?php

namespace App\Response;


use App\Model\Game;

/**
 * Interface ResponseBodyInterface
 * @package App\Response
 */
interface ResponseBodyInterface
{
    /**
     * Convert the response to its JSON representation.
     *
     * @param $data
     * @return string
     */
    public function toJson(Game $data): string;
}