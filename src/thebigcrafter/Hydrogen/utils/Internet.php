<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <hello@thebigcrafter.xyz>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen\utils;

use pocketmine\utils\InternetException;
use thebigcrafter\Hydrogen\EventLoop;
use thebigcrafter\Hydrogen\future\DeferredFuture;

class Internet
{
	public static function fetch(string $url)
	{
		$deferred = new DeferredFuture();

		EventLoop::defer(function () use($deferred, $url) {
			$res = \pocketmine\utils\Internet::getURL($url);

			if ($res instanceof InternetException) {
				throw $res;
			}

			$deferred->complete($res->getBody());
		});

		return $deferred->getFuture();
	}
}
