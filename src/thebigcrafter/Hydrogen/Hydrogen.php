<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <hello@thebigcrafter.xyz>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen;

use pocketmine\plugin\Plugin;
use pocketmine\Server;
use thebigcrafter\Hydrogen\future\Future;
use thebigcrafter\Hydrogen\future\FutureState;
use thebigcrafter\Hydrogen\tasks\CheckUpdatesTask;

class Hydrogen
{

	/**
	 * Notify if an update is available on Poggit.
	 */
	public static function checkForUpdates(Plugin $plugin) : void
	{
		Server::getInstance()->getAsyncPool()->submitTask(new CheckUpdatesTask($plugin->getName(), $plugin->getDescription()->getVersion()));
	}

	/**
	 * Creates a new fiber asynchronously using the given closure, returning a Future that is completed with the
	 * eventual return value of the passed function or will fail if the closure throws an exception.
	 */
	public static function async(\Closure $closure, mixed ...$args) : Future
	{
		static $run = null;

		$run ??= static function (FutureState $state, \Closure $closure, array $args) : void {
			$s = $state;
			$c = $closure;

			$state = $closure = null;

			try {
				$s->complete($c(...$args, ...($args = [])));
			} catch (\Throwable $exception) {
				$s->error($exception);
			}
		};

		$state = new FutureState();

		EventLoop::queue($run, $state, $closure, $args);

		return new Future($state);
	}

}
