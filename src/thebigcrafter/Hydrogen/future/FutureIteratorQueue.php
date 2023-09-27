<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <hello@thebigcrafter.xyz>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen\future;

use thebigcrafter\Hydrogen\eventLoop\Suspension;

class FutureIteratorQueue
{
	public array $items = [];

	public array $pending = [];

	public ?Suspension $suspension = null;
}
