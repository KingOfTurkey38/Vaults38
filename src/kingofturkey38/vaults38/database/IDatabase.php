<?php

declare(strict_types=1);

namespace kingofturkey38\vaults38\database;

interface IDatabase{

	const QUERY_INIT = "vaults38.init";
	const QUERY_LOAD = "vaults38.load";
	const QUERY_SAVE = "vaults38.save";
}