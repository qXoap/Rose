<?php

namespace rose\database\sqlite\retrieve;

use cooldogedev\libSQL\query\SQLiteQuery;
use SQLite3;

class SqliteRetrieveFactionData extends SQLiteQuery
{
    public function __construct(
        protected $name
    )
    {
    }

    public function onRun(SQLite3 $connection): void
    {
        $statement = $connection->prepare($this->query());

        $statement->bindParam(":name", $this->name);

        $request = $statement->execute();
        $this->setResult($request->fetchArray(SQLITE3_ASSOC) ?: null);

        $statement->close();
    }

    private function query(): string
    {
        return "SELECT * FROM factions WHERE name = :name";
    }
}