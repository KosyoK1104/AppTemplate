<?php

declare(strict_types=1);

namespace App\Shared\Event;

trait RecordEventsTrait
{
    /**
     * @var Event[] $events
     */
    private array $events = [];

    protected function recordEvent(Event $event) : void
    {
        $this->events[] = $event;
    }

    /**
     * @return Event[]
     */
    public function recordedEvents() : array
    {
        return $this->events;
    }
}
