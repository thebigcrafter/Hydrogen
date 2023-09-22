<?php

/*
 * This file is part of Hydrogen.
 * (c) thebigcrafter <hello@thebigcrafter.xyz>
 * This source file is subject to the Apache-2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace thebigcrafter\Hydrogen\form;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\CustomFormElement;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use dktapps\pmforms\ModalForm;
use Generator;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

final class AsyncForm {

	/**
	 * @param CustomFormElement[] $elements
	 */
	public static function custom(Player $player, string $title, array $elements) : Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new CustomForm(
			$title, $elements,
			function (Player $player, CustomFormResponse $result) use ($f) : void {
				$f($result);
			},
			function (Player $player) use ($f) : void {
				$f(null);
			}
		));
		return yield Await::ONCE;
	}

	/**
	 * @param MenuOption[] $options
	 */
	public static function menu(Player $player, string $title, string $text, array $options) : Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new MenuForm(
			$title, $text, $options,
			function (Player $player, int $selectedOption) use ($f) : void {
				$f($selectedOption);
			},
			function (Player $player) use ($f) : void {
				$f(null);
			}
		));
		return yield Await::ONCE;
	}

	public static function modal(Player $player, string $title, string $text, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no") : Generator {
		$f = yield Await::RESOLVE;
		$player->sendForm(new ModalForm(
			$title, $text,
			function (Player $player, bool $choice) use ($f) : void {
				$f($choice);
			},
			$yesButtonText, $noButtonText
		));
		return yield Await::ONCE;
	}
}
