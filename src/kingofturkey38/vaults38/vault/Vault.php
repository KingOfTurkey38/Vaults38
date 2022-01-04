<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\vault;

use JsonSerializable;
use pocketmine\item\Item;

class Vault implements JsonSerializable{

	private VaultMenu $menu;

	private bool $loading = false;
	private bool $unloading = false;

	public function __construct(
		private string $username,
		private int $number,
		private array $items,
	){
		$this->menu = new VaultMenu($this);
	}

	/**
	 * @return VaultMenu
	 */
	public function getMenu() : VaultMenu{
		return $this->menu;
	}


	public function getusername() : string{
		return $this->username;
	}

	public function getNumber() : int{
		return $this->number;
	}

	public function setItems(array $items) : void{
		$this->menu->getInvMenu()->getInventory()->setContents($items);
		$this->items = $items;
	}

	/**
	 * @return Item[]
	 */
	public function getItems() : array{
		return $this->items;
	}

	public function getIdentifier() : string{
		return $this->username . "." . $this->number;
	}

	public function jsonSerialize(){
		return $this->items;
	}

	public function setLoading(bool $loading) : void{
		$this->loading = $loading;
	}

	public function setUnloading(bool $unloading) : void{
		$this->unloading = $unloading;
	}

	public function isLoading() : bool{
		return $this->loading;
	}

	public function isUnloading() : bool{
		return $this->unloading;
	}
}