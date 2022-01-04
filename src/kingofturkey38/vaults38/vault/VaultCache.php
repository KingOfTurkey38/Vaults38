<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\vault;

class VaultCache{

	private static array $cache = [];

	public static function addToCache(Vault $vault) : void{
		self::$cache[$vault->getIdentifier()] = $vault;
	}

	public static function removeFromCache(Vault $vault) : void{
		unset(self::$cache[$vault->getIdentifier()]);
	}

	public static function getFromCache(string $identifier) : ?Vault{
		return self::$cache[$identifier] ?? null;
	}
}