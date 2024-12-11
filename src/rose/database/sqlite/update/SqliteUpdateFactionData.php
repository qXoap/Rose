<?php

namespace rose\database\sqlite\update;

use cooldogedev\libSQL\query\SQLiteQuery;
use SQLite3;

class SqliteUpdateFactionData extends SQLiteQuery
{
    public function __construct(
        protected string $name,
        protected array $data
    )
    {
    }

    public function onRun(SQLite3 $connection): void
    {
        $statement = $connection->prepare($this->query());

        $officers = json_encode($this->data['officers']);
        $members = json_encode($this->data['members']);
        $claimData = json_encode($this->data['claim']);

        $statement->bindParam(":p", $this->data['power']);
        $statement->bindParam(":b", $this->data['balance']);
        $statement->bindParam(":d", $this->data['dtr']);
        $statement->bindParam(":cl", $claimData);
        $statement->bindParam(":hp", $this->data['homePosition']);
        $statement->bindParam(":l", $this->data['leader']);
        $statement->bindParam(":o", $officers);
        $statement->bindParam(":m", $members);
        $statement->bindParam(":name", $this->name);

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "UPDATE factions SET power = :p, balance = :b, dtr :d, claim = :cl, homePosition = :hp, leader = :l, officers = :o, members = :m WHERE name = :name";
    }
}