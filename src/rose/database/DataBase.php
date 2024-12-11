<?php

namespace rose\database;

use cooldogedev\libSQL\ConnectionPool;
use cooldogedev\libSQL\query\SQLQuery;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

use rose\database\mysql\create\MySqlCreateFaction;
use rose\database\mysql\delete\MySqlDeleteFaction;
use rose\database\mysql\MySqlCreateFactionsTable;
use rose\database\mysql\retrieve\MySqlRetrieveFactionData;
use rose\database\mysql\update\MySqlUpdateFactionData;

use rose\database\sqlite\delete\SqliteDeleteFaction;
use rose\database\sqlite\retrieve\SqliteRetrieveFactionData;
use rose\database\sqlite\SqliteCreateFactionsTable;
use rose\database\sqlite\update\SqliteCreateFaction;
use rose\database\sqlite\update\SqliteUpdateFactionData;

use Closure;

class DataBase
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    private ConnectionPool $connectionPool;

    private bool $isMysql;

    public function __construct(PluginBase $base)
    {
        self::setInstance($this);

        $data = $base->getConfig()->get('database');

        $this->connectionPool = new ConnectionPool($base, $data);
        $this->isMysql = $data['provider'] !== 'mysql';

        $this->createTables();
    }

    private function submit(SQLQuery $query, Closure $onSuccess = null, Closure $onFail = null): void
    {
        $this->connectionPool->submit($query, $onSuccess, $onFail);
    }

    public function createTables(): void
    {
        $queryClass = $this->isMysql ? MySqlCreateFactionsTable::class : SqliteCreateFactionsTable::class;

        $this->submit(new $queryClass);
    }

    public function createFaction(string $name, array $data): void
    {
        $queryClass = $this->isMysql ? MySqlCreateFaction::class : SqliteCreateFaction::class;

        $this->submit(new $queryClass($name, $data));
    }

    public function getFactionData(string $name, Closure $onSuccess = null, Closure $onFail = null): void
    {
        $queryClass = $this->isMysql ? MySqlRetrieveFactionData::class : SqliteRetrieveFactionData::class;

        $this->submit(new $queryClass($name), $onSuccess, $onFail);
    }

    public function updateFactionData(string $name, array $data, Closure $onSuccess = null, Closure $onFail = null): void
    {
        $queryClass = $this->isMysql ? MySqlUpdateFactionData::class : SqliteUpdateFactionData::class;

        $this->submit(new $queryClass($name, $data), $onSuccess, $onFail);
    }

    public function deleteFaction(string $name, Closure $onSuccess = null, Closure $onFail = null): void
    {
        $queryClass = $this->isMysql ? MySqlDeleteFaction::class : SqliteDeleteFaction::class;

        $this->submit(new $queryClass($name), $onSuccess, $onFail);
    }
}