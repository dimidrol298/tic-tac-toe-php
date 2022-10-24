<?php

namespace App\Model;


use Opis\Database\Database as OpisDatabase;
use Opis\Database\Connection;

/**
 * Class DataBase
 * @package App\Model
 */
class DataBase
{
    /**
     * @var OpisDatabase|null
     */
    protected ?OpisDatabase $dbConn = null;

    /**
     * DataBase constructor.
     */
    public function __construct()
    {
        $connection = new Connection(
            'mysql:host='.DB_HOST.';dbname='.DB_DATABASE_NAME,

            DB_USERNAME,
            DB_PASSWORD
        );

        $this->dbConn = new OpisDatabase($connection);
    }
}