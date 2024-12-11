<?php

namespace rose\extension\home;

use pocketmine\world\Position;
use rose\utils\PositionUtils;

class FactionHome
{
    public function __construct(
        private ?Position $position = null
    )
    {
    }

    public function getPosition(): Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position = null): void
    {
        $this->position = $position;
    }

    public function toString(): ?string
    {
        return PositionUtils::toString($this->position);
    }
}