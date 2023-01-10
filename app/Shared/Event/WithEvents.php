<?php

declare(strict_types=1);

namespace App\Shared\Event;

interface WithEvents
{
    /**
     * @return Event[]
     */
    public function events() : array;
}
