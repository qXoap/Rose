<?php

namespace rose\database\mysql\update;

use cooldogedev\libSQL\query\MySQLQuery;
use mysqli;

class MySqlUpdateFactionData extends MySQLQuery
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
            $data->power,
            $data->balance,
            $data->dtr,
            $claimData,
            $data->homePosition,
            $data->leader,
            $officers,
            $members,
            $this->name
        );

        $statement->execute();
        $statement->close();
    }

    private function query(): string
    {
        return "UPDATE factions SET power = ?, balance = ?, dtr = ?, claimPosition = ?, homePosition = ?, leader = ?, officers = ?, members = ? WHERE name = ?";
    }
}