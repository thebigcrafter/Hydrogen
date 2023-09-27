<?php

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