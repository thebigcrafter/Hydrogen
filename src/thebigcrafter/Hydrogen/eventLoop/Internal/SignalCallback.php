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
final class SignalCallback extends DriverCallback
{
	public function __construct(
		string $id,
		\Closure $closure,
		public readonly int $signal
	) {
		parent::__construct($id, $closure);
	}
}
