<?php

namespace rose\extension\claim;

use pocketmine\world\Position;
use rose\utils\PositionUtils;

class FactionClaim
{
    public function __construct(
        private ?Position $firstPosition = null,
        private ?Position $secondPosition = null
    )
    {
    }

    public function getFirstPosition(): ?Position
    {
        return $this->firstPosition;
    }

    public function getSecondPosition(): ?Position
    {
        return $this->secondPosition;
    }

    public function setFirstPosition(?Position $firstPosition): void
    {
        $this->firstPosition = $firstPosition;
    }

    public function setSecondPosition(?Position $secondPosition): void
    {
        $this->secondPosition = $secondPosition;
    }

    public function inside(Position $position): bool
    {
        if (is_null($this->firstPosition)) {
            return false;
        }

        if (is_null($this->secondPosition)) {
            return false;
        }

        $positionX = $position->getX();
        $positionZ = $position->getZ();

        $positionWorld = $position->getWorld()->getFolderName();
        $firstWorld = $this->firstPosition->getWorld()->getFolderName();

        if ($positionWorld !== $firstWorld) {
            return false;
        }

        $firstX = $this->firstPosition->getX();
        $secondX = $this->secondPosition->getX();

        $firstZ = $this->firstPosition->getZ();
        $secondZ = $this->secondPosition->getZ();

        $minX = min($firstX, $secondX);
        $maxX = max($firstX, $secondX);
        $minZ = min($firstZ, $secondZ);
        $maxZ = max($firstZ, $secondZ);

        return ($positionX >= $minX && $positionX <= $maxX && $positionZ >= $minZ && $positionZ <= $maxZ);
    }

    public function toArray(): array
    {
        return [
            'firstPosition' => PositionUtils::toString($this->firstPosition),
            'secondPosition' => PositionUtils::toString($this->secondPosition)
        ];
    }
}