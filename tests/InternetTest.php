<?php

declare(strict_types=1);

namespace pocketmine\utils {
	final class InternetException extends \RuntimeException
	{
	}

	final class Internet
	{
		public static ?object $response = null;

		public static function getURL(string $url) : ?object
		{
			return self::$response;
		}
	}
}

namespace {
	use pocketmine\utils\Internet as PocketMineInternet;
	use pocketmine\utils\InternetException;
	use thebigcrafter\Hydrogen\utils\Internet;

	final class TestResponse
	{
		public function getBody() : string
		{
			return 'hydrogen-test-response';
		}
	}

	$root = dirname(__DIR__);
	spl_autoload_register(static function (string $class) use ($root) : void {
		$prefix = 'thebigcrafter\\Hydrogen\\';
		if (!str_starts_with($class, $prefix)) {
			return;
		}

		$relative = substr($class, strlen($prefix));
		require $root . '/src/thebigcrafter/Hydrogen/' . str_replace('\\', '/', $relative) . '.php';
	});

	try {
		Internet::fetch('https://example.invalid')->await();
		throw new \RuntimeException('Expected InternetException was not propagated');
	} catch (InternetException $exception) {
		$expected = 'Unable to fetch https://example.invalid';
		if ($exception->getMessage() !== $expected) {
			throw new \RuntimeException('Unexpected exception message: ' . $exception->getMessage());
		}
	}

	PocketMineInternet::$response = new TestResponse();
	$body = Internet::fetch('https://example.test')->await();
	if ($body !== 'hydrogen-test-response') {
		throw new \RuntimeException('Unexpected response body: ' . $body);
	}

	echo "Hydrogen Internet future propagation passed\n";
}
