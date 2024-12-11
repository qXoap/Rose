<?php

namespace rose\session;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use rose\data\DataCreator;

class SessionFactory
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    /** @var Session[] */
    private array $sessions = [];

    private DataCreator $dataCreator;

    public function __construct()
    {
        self::setInstance($this);

        $this->dataCreator = new DataCreator("players");
    }

    public function getDataCreator(): DataCreator
    {
        return $this->dataCreator;
    }

    public function create(Player $player): void
    {
        $factionName = $this->dataCreator->get($player->getName()); // Returns null by default

        $this->sessions[$player->getName()] = new Session($player->getName(), $factionName);
    }

    public function get(string $name): ?Session
    {
        return $this->sessions[$name] ?? null;
    }

    public function delete(string $name): void
    {
        unset($this->sessions[$name]);
    }

    public function getAll(): array
    {
        return $this->sessions;
    }

    public function save(): void
    {
        foreach ($this->sessions as $key => $session) {
            $this->dataCreator->set($key, $session->getFactionName());
        }
    }
}