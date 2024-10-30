<?php

namespace App;

use Illuminate\Database\Connectors\SqlServerConnector;
use PDO;

class CustomSqlServerConnector extends SqlServerConnector
{
    protected $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        //PDO::ATTR_STRINGIFY_FETCHES => false,
    ];
}
