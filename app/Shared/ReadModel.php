<?php

declare(strict_types=1);

namespace App\Shared;

use JsonSerializable;

abstract class ReadModel implements JsonSerializable
{
    abstract public function toArray() : array;

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}
