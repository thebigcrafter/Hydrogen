# Hydrogen

[![CI](https://github.com/thebigcrafter/Hydrogen/actions/workflows/ci.yml/badge.svg)](https://github.com/thebigcrafter/Hydrogen/actions/workflows/ci.yml)

Hydrogen is a shared PHP library for thebigcrafter PocketMine-MP plugins. It is packaged as a Virion library and is intended to be embedded into a plugin build; it is not a standalone PocketMine-MP plugin or server package.

## What it provides

- An event-loop facade with queue, deferred, delayed, repeated, stream, and signal callbacks.
- Driver selection through `thebigcrafter\Hydrogen\eventLoop\DriverFactory`, including the available platform-specific drivers and the stream-select fallback.
- `Future` and `DeferredFuture` primitives for composing and awaiting asynchronous work.
- Plugin update checks through `Hydrogen::checkForUpdates()`.
- Configuration helpers such as `HConfig::verifyConfigVersion()` and `HConfig::resetConfig()`.
- Small utilities for internet access, item/block conversion, string conversion, and array operations.

The public API is under the `thebigcrafter\Hydrogen` namespace. Consumers should use the documented facade and value types; classes under `eventLoop\Internal` are implementation details and are not a stable integration surface.

## Requirements

- PHP 8.1 or newer.
- PocketMine-MP API 5 when using the plugin-facing helpers and Virion integration.
- `sof3/await-generator` at runtime for the future implementation.

Optional event-loop drivers may require their corresponding PHP extensions. Hydrogen can use the stream-select driver when a faster native driver is not available.

## Using Hydrogen in a plugin

Hydrogen is consumed as a Virion dependency by a PocketMine-MP plugin. Add the library to the plugin's Composer/Virion dependency set, import the public classes you need, and let the plugin's normal build process embed the library. Do not copy `src/` into a plugin manually or instantiate internal driver classes directly.

For example, a plugin can schedule asynchronous work through the event-loop facade:

```php
use thebigcrafter\Hydrogen\EventLoop;

EventLoop::defer(function () : void {
    // Work that should run on the next event-loop turn.
});
```

The callback and future APIs are asynchronous primitives, not a replacement for PocketMine-MP's main-thread safety rules. Plugin code remains responsible for deciding which operations may run away from the main thread and for handling cancellation and errors.

## Development and verification

Install the development dependencies and run the repository checks:

```text
composer install
composer run-script analyse
composer run-script test
vendor/bin/php-cs-fixer fix --dry-run --ansi --diff
```

The GitHub Actions workflow runs the Hydrogen runtime regression test, PHPStan, and the PHP code-style check. The runtime test covers the Future error-propagation contract used by asynchronous network checks; passing it does not by itself prove every event-loop driver works on every host.

The repository's build configuration uses DevTools to package and validate the Virion library. The exact tool reference is maintained by the build service; user documentation intentionally describes the tool by function instead of copying a build revision that may change independently of Hydrogen.

Hydrogen's optional `REVOLT_DRIVER` path is deliberately guarded: the selected class must be confirmed with `class_exists()` and `is_subclass_of()` before it is instantiated. The same explicit contract is used for internal callback-class inspection. This keeps the runtime selection visible to packaging and static checks without changing the public API.

## Compatibility and maintenance

Hydrogen targets the PHP and PocketMine-MP API ranges declared in `composer.json` and `virion.yml`. Check those manifests for the current compatibility contract. The repository uses Dependabot for Composer and GitHub Actions updates; review updates for API and runtime compatibility before merging them.

## License

Hydrogen is licensed under the [Apache License 2.0](LICENSE).
