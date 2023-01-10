<?php

declare(strict_types=1);

namespace App\Shared;

use InvalidArgumentException;

class NullableAbstractString
{
    public function __construct(
        public readonly ?string $value
    ) {
        $this->guard($value);
    }

    public static function make(?string $value) : self
    {
        return new self($value);
    }

    public static function isValid(mixed $value) : bool
    {
        return is_null($value) || is_string($value);
    }

    private function guard(?string $value) : void
    {
        if (!self::isValid($value)) {
            throw new InvalidArgumentException('asd');
        }
    }

    public function __toString() : string
    {
        return $this->value ?? '';
    }

    public function equals(NullableAbstractString $other) : bool
    {
        return $this->value === $other->value;
    }

    public function doesNotEqual(NullableAbstractString $other) : bool
    {
        return !$this->equals($other);
    }

    public function isNull() : bool
    {
        return is_null($this->value);
    }

    public function isNotNull() : bool
    {
        return !$this->isNull();
    }
}
