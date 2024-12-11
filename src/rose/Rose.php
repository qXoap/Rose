<?php

namespace rose;

use CortexPE\Commando\PacketHooker;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use rose\database\DataBase;
use rose\factory\FactionFactory;
use rose\session\SessionFactory;

class Rose extends PluginBase
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    protected function onEnable(): void
    {
        self::setInstance($this);

        /** SAVE RESOURCES */
        $this->saveDefaultConfig();
        $this->saveResource("database/sqlite.db");

        /** LOAD VIRIONS */
        if (!PacketHooker::isRegistered()) PacketHooker::register($this);

        /** LOAD DATABASE */
        new DataBase($this);

        /** LOAD HANDLERS */
        $this->loadListeners(new EventHandler()); // add another handlers here
    }

    private function loadListeners(Listener... $listeners): void
    {
        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }

    protected function onDisable(): void
    {
        FactionFactory::getInstance()->save();
        SessionFactory::getInstance()->save();
    }
}