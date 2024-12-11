<?php

namespace rose\data;

use pocketmine\utils\Config;
use rose\Rose;
use Symfony\Component\Filesystem\Path;

class DataCreator
{
    private Config $config;

    public function __construct(string $path)
    {
        $path = Path::join(Rose::getInstance()->getDataFolder(), "factions.yml");

        if (!is_file($path)) {
            Rose::getInstance()->saveResource($path);
        }

        $this->config = new Config($path, Config::YAML);
    }

    public function set(string $key, mixed $value): void
    {
        $this->config->set($key, $value);
        $this->config->save();
    }

    public function get(string $key): mixed
    {
        return $this->config->get($key, null);
    }

    public function delete(string $key): void
    {
        $this->config->remove($key);
        $this->config->save();
    }
}