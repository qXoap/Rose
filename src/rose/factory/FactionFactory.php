<?php

namespace rose\factory;

use pocketmine\utils\SingletonTrait;
use rose\data\DataCreator;
use rose\database\DataBase;
use rose\extension\Faction;

class FactionFactory
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    /** @var Faction[] */
    private array $factions = [];

    private DataCreator $dataCreator;

    public function __construct()
    {
        self::setInstance($this);

        $this->dataCreator = new DataCreator("factions");
    }

    public function load(): void
    {
        $factions = $this->dataCreator->get("factions");

        if (!is_array($factions)) {
            return;
        }

        foreach ($factions as $name) {
            $this->create(new Faction($name));
        }
    }

    public function create(Faction $faction): void
    {
        $this->factions[$faction->getName()] = $faction;
    }

    public function get(string $name): ?Faction
    {
        return $this->factions[$name] ?? null;
    }

    public function delete(string $name): void
    {
        $faction = $this->get($name);

        if (is_null($faction)) {
            return;
        }

        unset($this->factions[$name]);

        if ($faction->isRegistered()) {
            DataBase::getInstance()->deleteFaction($name);
        }
    }

    public function getAll(): array
    {
        return $this->factions;
    }

    public function save(): void
    {
        $this->dataCreator->set("factions", array_keys($this->factions));

        foreach ($this->factions as $faction) {
            $faction->save();
        }
    }
}