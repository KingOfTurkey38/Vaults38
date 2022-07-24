<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\database;

use Generator;
use kingofturkey38\vaults38\Main;
use kingofturkey38\vaults38\vault\Vault;
use kingofturkey38\vaults38\vault\VaultCache;
use pocketmine\item\Item;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use SOFe\AwaitGenerator\Await;

class Database implements IDatabase{

	private DataConnector $database;

	public function __construct(private Main $plugin){
		$this->database = libasynql::create($this->plugin, $this->plugin->getConfig()->get("database"), ["sqlite" => "sqlite.sql", "mysql" => "queries.sql"]);
		$this->database->executeGeneric(IDatabase::QUERY_INIT);
	}

	public function awaitSelect(string $queryName, array $args = []) : Generator{
		$this->database->executeSelect($queryName, $args, yield Await::RESOLVE);
		return yield AWAIT::ONCE;
	}

	public function awaitInsert(string $queryName, array $args = []) : Generator{
		$this->database->executeInsert($queryName, $args, yield Await::RESOLVE);
		return yield AWAIT::ONCE;
	}

	public function loadVault(string $username, int $number){
		$vault = new Vault($username, $number, []);
		$vault->setLoading(true);
		VaultCache::addToCache($vault);

		$data = yield from $this->awaitSelect(self::QUERY_LOAD, ["username" => $username, "number" => $number]);
		if(isset($data[0]["data"])){
			$items = [];
			foreach(json_decode($data[0]["data"], true) as $k => $v){
				$items[$k] = Item::jsonDeserialize($v);
			}
			$vault->setItems($items);
		}

		$vault->setLoading(false);

		return $vault;
	}

	public function unloadVault(Vault $vault) : void{
		$vault->setUnloading(true);
		Await::f2c(function() use ($vault){
			yield from $this->awaitInsert(self::QUERY_SAVE, ["username" => $vault->getusername(), "data" => json_encode($vault->getItems()), "number" => $vault->getNumber()]);
			VaultCache::removeFromCache($vault);
			$vault->setUnloading(false);
		});
	}
}
