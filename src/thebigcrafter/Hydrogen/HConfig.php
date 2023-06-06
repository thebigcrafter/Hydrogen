<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <thebigcrafterteam@proton.me>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen;

use pocketmine\utils\Config;
use function copy;
use function rename;
use function unlink;
use function version_compare;

class HConfig {
	/**
	 * Check config file version
	 */
	public static function verifyConfigVersion(Config $config, string $version) : bool {
		/** @var string $currentVersion */
		$currentVersion = $config->get("version");

		if(version_compare($currentVersion, $version, "<>")) {
			return false;
		}
		return true;
	}

	/**
	 * Reset config file by using a template file
	 */
	public static function resetConfig(string $templatePath, string $configPath, bool $hardReset = false) : bool {
		if($hardReset) {
			if(unlink($configPath) && copy($templatePath, $configPath)) {
				return true;
			} else {
				return false;
			}
		} else {
			if(rename($configPath, $configPath . "_old") && copy($templatePath, $configPath)) {
				return true;
			}

			return false;
		}
	}
}
