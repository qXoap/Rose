<?php

namespace rose\extension;

use pocketmine\utils\TextFormat;
use rose\database\DataBase;
use rose\extension\claim\FactionClaim;
use rose\extension\home\FactionHome;
use rose\Rose;
use rose\utils\PositionUtils;

class Faction
{
    private bool $registered = false;

    public function __construct(
        private readonly string $name,
        private string $leader = "",
        private int $power = 0,
        private int $balance = 0,
        private float $dtr = 1.0,
        private ?FactionHome $home = null,
        private ?FactionClaim $claim = null,
        private array $officers = [],
        private array $members = []
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRegistered(): bool
    {
        return $this->registered;
    }

    private function initialize(): void
    {
        DataBase::getInstance()->getFactionData($this->name, function (?array $data): void
        {
            if (is_null($data)) {
                return;
            }

            $this->registered = true;

            $this->power = $data['power'];
            $this->balance = $data['balance'];
            $this->dtr = $data['kdr'];

            $this->home = new FactionHome(PositionUtils::fromString($data['home']));

            $homeData = $data['home'];

            $this->claim = new FactionClaim(
                PositionUtils::fromString($homeData['firstPosition']),
                PositionUtils::fromString($homeData['secondPosition'])
            );

            $this->leader = $data['leader'];
            $this->officers = json_decode($data['officers'], true);
            $this->members = json_decode($data['members'], true);

            Rose::getInstance()->getLogger()->info(TextFormat::colorize(
                "&aFaction &e" . $this->name . "&a Data Successfully loaded"
            ));
        });
    }

    public function getLeader(): string
    {
        return $this->leader;
    }

    public function getPower(): int
    {
        return $this->power;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getDtr(): float
    {
        return $this->dtr;
    }

    public function getClaim(): ?FactionClaim
    {
        return $this->claim;
    }

    public function getHome(): ?FactionHome
    {
        return $this->home;
    }

    public function getOfficers(): array
    {
        return $this->officers;
    }

    public function getMembers(): array
    {
        return $this->members;
    }

    private function toArray(): array
    {
        return [
            'power' => $this->power,
            'balance' => $this->balance,
            'dtr' => $this->dtr,
            'claim' => $this->claim->toArray(),
            'home' => $this->home->toString(),
            'officers' => $this->officers,
            'members' => $this->members
        ];
    }

    public function save(): void
    {
        if ($this->registered) {
            DataBase::getInstance()->updateFactionData($this->name, $this->toArray());
        } else {
            DataBase::getInstance()->createFaction($this->name, $this->toArray());
        }
    }
}