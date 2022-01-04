<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\vault;

use Closure;
use kingofturkey38\vaults38\Main;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

class VaultMenu{


	private InvMenu $invMenu;

	public function __construct(private Vault $vault){
		$this->invMenu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$this->invMenu->setName($this->vault->getusername() . " #" . $this->vault->getNumber());

		$this->invMenu->getInventory()->setContents($this->vault->getItems());

		$this->invMenu->setInventoryCloseListener(Closure::fromCallable([$this, "onClose"]));
	}

	public function send(Player $player) : void{
		$this->invMenu->send($player);
	}

	/**
	 * @return InvMenu
	 */
	public function getInvMenu() : InvMenu{
		return $this->invMenu;
	}

	public function onClose(Player $player, Inventory $inventory) : void{
		$viewers = $inventory->getViewers();
		foreach($viewers as $key => $viewer){
			if($viewer->getId() === $player->getId()){
				unset($viewers[$key]);
			}
		}

		if(empty($viewers)){
			$this->vault->setItems($inventory->getContents());
			Main::getInstance()->getDatabase()->unloadVault($this->vault);
		}
	}
}