<?php

namespace rose\database\mysql\create;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;

class MySqlCreateFaction extends MySQLQuery
{
    private string $name;
    private string $data;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = json_encode($data);
    }

    public function onRun(mysqli $connection): void
    {
        $data = json_decode($this->data);

        $officers = json_encode($data->officers);
        $members = json_encode($data->members);
        $claimData = json_encode($data->claimPosition);

        $statement = $connection->prepare($this->query());

        $statement->bind_param(
            "sssssssss",
            $this->name,
            $data->balance,
            $data->dtr,
            $claimData,
            $data->homePosition,
            $data->leader,
            $officers,
            $members
        );

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "INSERT INTO factions (name, power, balance, dtr, claim, homePosition, leader, officers, members) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
}