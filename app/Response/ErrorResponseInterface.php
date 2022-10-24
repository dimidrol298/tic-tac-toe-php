<?php

namespace App\Response;


/**
 * Interface ErrorResponseInterface
 * @package App\Response
 */
interface ErrorResponseInterface
{
    /**
     * Convert the response to its JSON representation.
     *
     * @param $data
     * @return string
     */
    public function toJson(string $data): string;
}