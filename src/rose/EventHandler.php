<?php

namespace rose;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use rose\session\SessionFactory;

class EventHandler implements Listener
{
    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        SessionFactory::getInstance()->create($event->getPlayer());
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $session = SessionFactory::getInstance()->get($player->getName());

        if (!is_null($session)) {
            $session->save();
        }
    }
}