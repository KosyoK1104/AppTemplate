<?php

declare(strict_types=1);

namespace App\Shared\Identification;

use App\Shared\Identification\Exceptions\InvalidUuidArgument;
use Ramsey\Uuid\Uuid as RamseyUuid;
use Ramsey\Uuid\UuidInterface as RamseyUuidInterface;

class Uuid
{
    private RamseyUuidInterface $value;

    public function __construct(RamseyUuidInterface $value)
    {
        $this->guard($value);
        $this->value = $value;
    }

    private function guard(mixed $value) : void
    {
        if (!self::isValid($value)) {
            throw InvalidUuidArgument::forValue($value);
        }
    }

    public function value() : string
    {
        return $this->value->toString();
    }

    public function equals(Uuid $other) : bool
    {
        return $this->value === $other->value;
    }

    public function __toString() : string
    {
        return $this->value->toString();
    }

    public static function fromString(string $value) : Uuid
    {
        return new self(RamseyUuid::fromString($value));
    }

    public static function isValid(string $value) : bool
    {
        return RamseyUuid::isValid($value);
    }

    public static function fromBinary(string $value) : Uuid
    {
        return new self (RamseyUuid::fromBytes($value));
    }

    public function toBinary() : string
    {
        return $this->value->getBytes();
    }
}
