<?php

namespace rose\database\sqlite;

use cooldogedev\libSQL\query\SQLiteQuery;
use SQLite3;

class SqliteCreateFactionsTable extends SQLiteQuery
{
    public function onRun(SQLite3 $connection): void
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
            claim VARCHAR(255) NULLABLE,
            homePosition VARCHAR(16) NULLABLE,
            leader VARCHAR(255) NOT NULL,
            officers VARCHAR(255) NOT NULL,
            members VARCHAR(255) NOT NULL
        )";
    }
}