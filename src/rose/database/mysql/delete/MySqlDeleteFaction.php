<?php

namespace rose\database\mysql\delete;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;

class MySqlDeleteFaction extends MySQLQuery
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function onRun(mysqli $connection): void
    {
        $statement = $connection->prepare($this->query());

        $statement->bind_param("s", $this->name);

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "DELETE FROM factions WHERE name = ?";
    }
}