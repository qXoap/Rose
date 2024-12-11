<?php

namespace rose\utils;

use pocketmine\Server;
use pocketmine\world\Position;

class PositionUtils
{
    public static function toString(?Position $position): ?string
    {
        if (is_null($position)) {
            return null;
        }

        $x = $position->getX();
        $y = $position->getY();
        $z = $position->getZ();

        $world = $position->getWorld()->getFolderName();

        return $x . ":" . $y . ":" . $z . ":" . $world;
    }

    public static function fromString(?string $string): ?Position
    {
        if (is_null($string)) {
            return null;
        }

        $data = explode(":", $string);

        $world = Server::getInstance()
            ->getWorldManager()
            ->getWorldByName($data[3]);

        return new Position($data[0], $data[1], $data[2], $world);
    }
}