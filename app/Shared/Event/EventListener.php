<?php

declare(strict_types=1);

namespace App\Shared\Event;

interface EventListener
{
    public function handle(Event $event) : void;

    public function isSubscribedTo(Event $event) : bool;
}
