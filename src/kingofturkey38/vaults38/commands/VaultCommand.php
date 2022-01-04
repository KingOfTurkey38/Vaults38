<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\commands;

use kingofturkey38\vaults38\Main;
use kingofturkey38\vaults38\vault\Vault;
use kingofturkey38\vaults38\vault\VaultCache;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use SOFe\AwaitGenerator\Await;

class VaultCommand extends Command implements PluginOwned{

	public function __construct(private Main $plugin){
		parent::__construct("vault", "Open your private vault", null, ["pv", "vaults", "privatevault"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if($sender instanceof Player){
			$username = $sender->getName();
			$usage = "§c/{$commandLabel} <number>";

			if(!isset($args[0])){
				$sender->sendMessage($usage);
				return;
			}

			$number = intval($args[0]);

			if($number <= 0){
				$sender->sendMessage($usage);
				return;
			}

			//allow OP players to open as many pvs as they want
			if(!$sender->hasPermission("vaults38.vault." . $number) && !$sender->getServer()->isOp($sender->getName())){
				$sender->sendMessage("§cYou don't have permission to use the #$number vault");
				return;
			}

			if(isset($args[1]) && $sender->hasPermission("vaults38.edit.others")){
				$username = $args[1];
			}

			if(($vault = VaultCache::getFromCache($username . "." . $number)) instanceof Vault){
				if($vault->isLoading()){
					$sender->sendMessage("§cThis vault is loading, please try again later");
					return;
				}

				if($vault->isUnloading()){
					$sender->sendMessage("§cThis vault is unloading, please try again later");
					return;
				}

				$vault->getMenu()->send($sender);
				return;
			}

			Await::f2c(function() use ($sender, $username, $number){
				/** @var Vault $vault */
				$vault = yield $this->plugin->getDatabase()->loadVault($username, $number);
				$vault->getMenu()->send($sender);
			});
		}
	}

	public function getOwningPlugin() : Plugin{
		return $this->plugin;
	}
}