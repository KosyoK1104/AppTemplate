<?php

declare(strict_types=1);

namespace App\Shared\Event;

interface RecordEvents
{
    /**
     * @return Event[]
     */
    public function events() : array;
}
