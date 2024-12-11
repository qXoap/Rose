<?php

namespace rose\session;

use pocketmine\player\Player;
use pocketmine\Server;
use rose\extension\Faction;
use rose\factory\FactionFactory;

class Session
{
    public function __construct(
        private readonly string $name,
        private ?string         $factionName = null
    )
    {
        $this->load();
    }

    private function load(): void
    {
        if (is_null($this->factionName)) {
            return;
        }

        $faction = FactionFactory::getInstance()->get($this->factionName);

        if (!is_null($faction)) {
            return;
        }

        $this->factionName = null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPlayer(): ?Player
    {
        return Server::getInstance()->getPlayerExact($this->name);
    }

    public function getFactionName(): ?string
    {
        return $this->factionName;
    }

    public function setFaction(?string $factionName): void
    {
        $this->factionName = $factionName;
    }

    public function getFaction(): ?Faction
    {
        if (is_null($this->factionName)) {
            return null;
        }

        return FactionFactory::getInstance()->get($this->factionName);
    }

    public function save(): void
    {
        SessionFactory::getInstance()
            ->getDataCreator()
            ->set($this->name, $this->factionName);

        SessionFactory::getInstance()->delete($this->name);
    }
}