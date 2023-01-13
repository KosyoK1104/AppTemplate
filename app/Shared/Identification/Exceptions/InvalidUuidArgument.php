<?php

declare(strict_types=1);

namespace App\Shared\Identification\Exceptions;

use InvalidArgumentException;

final class InvalidUuidArgument extends InvalidArgumentException
{
    public static function forClass(string $class) : self
    {
        return new self(sprintf('Invalid argument for class %s', $class));
    }

    public static function forValue(string $value) : self
    {
        return new self(sprintf('Invalid argument for value %s', $value));
    }
}
