<?php

declare(strict_types=1);

namespace App\Shared;

use InvalidArgumentException;

class IdentifiyingInt
{
    public function __construct(
        public readonly int $value
    ) {
        $this->guard($value);
    }

    public static function make(int $value) : self
    {
        return new self($value);
    }

    public static function isValid(int $value) : bool
    {
        return true;
    }

    private function guard(int $value) : void
    {
        if (!self::isValid($value)) {
            throw new InvalidArgumentException('asd');
        }
    }
}