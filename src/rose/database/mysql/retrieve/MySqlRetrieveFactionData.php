<?php

namespace rose\database\mysql\retrieve;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;

class MySqlRetrieveFactionData extends MySQLQuery
{
    public function __construct(
        protected $name
    )
    {
    }

    public function onRun(mysqli $connection): void
    {
        $statement = $connection->prepare($this->query());

        $statement->bind_param("s", $this->name);

        $statement->execute();
        $this->setResult($statement->get_result()?->fetch_assoc() ?: null);

        $statement->close();
    }

    private function query(): string
    {
        return "SELECT * FROM factions WHERE name = ?";
    }
}