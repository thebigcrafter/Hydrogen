<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <hello@thebigcrafter.xyz>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen\eventLoop\Internal;

/** @internal */
final class TimerCallback extends DriverCallback
{
	public function __construct(
		string $id,
		public readonly float $interval,
		\Closure $callback,
		public float $expiration,
		public readonly bool $repeat = false
	) {
		parent::__construct($id, $callback);
	}
}
