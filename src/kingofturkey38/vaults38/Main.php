<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38;

use kingofturkey38\vaults38\commands\VaultCommand;
use kingofturkey38\vaults38\database\Database;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

	private Database $database;

	private static Main $instance;

	protected function onEnable() : void{
		self::$instance = $this;

		$this->saveDefaultConfig();
		$this->database = new Database($this);

		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->register("vaults38", new VaultCommand($this));
	}


	public function getDatabase() : Database{
		return $this->database;
	}

	public static function getInstance() : Main{
		return self::$instance;
	}
}
