<?php


namespace App\Response;


/**
 * Class Error
 * @package App\Response
 */
class Error implements ErrorResponseInterface
{
    /**
     * @param string $data
     * @return string
     */
    public function toJson(string $data): string
    {
        $error = [
            'error' => true,
            'detail' => $data,
        ];

        return json_encode($error, JSON_PRETTY_PRINT);
    }
}