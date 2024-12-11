<?php

namespace rose\database\mysql;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;

class MySqlCreateFactionsTable extends MySQLQuery
{
    public function onRun(mysqli $connection): void
    {
        $statement = $connection->prepare($this->query());

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "CREATE TABLE IF NOT EXISTS factions (
            factionName VARCHAR(16) PRIMARY KEY UNIQUE NOT NULL,
            power INTEGER NOT NULL,
            balance INTEGER NOT NULL,
            dtr FLOAT NOT NULL,
            claim VARCHAR(255) NOT NULL,
            homePosition VARCHAR(16) NOT NULL,
            leader VARCHAR(255) NOT NULL,
            officers VARCHAR(255) NOT NULL,
            members VARCHAR(255) NOT NULL
        )";
    }
}