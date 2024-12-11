<?php

namespace rose\database\sqlite\delete;

use cooldogedev\libSQL\query\SQLiteQuery;
use SQLite3;

class SqliteDeleteFaction extends SQLiteQuery
{
    public function __construct(
        protected string $name
    )
    {
    }

    public function onRun(SQLite3 $connection): void
    {
        $statement = $connection->prepare($this->query());

        $statement->bindParam(":name", $this->name);

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "DELETE FROM factions WHERE name = :name";
    }
}