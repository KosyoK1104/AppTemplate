<?php

declare(strict_types=1);

namespace App\Shared\Security;

use SodiumException;

class SecuredString
{
    public function __construct(
        protected string $value
    ) {
    }

    public static function make(string $value) : static
    {
        return new static($value);
    }

    public function __toString() : string
    {
        return '**********';
    }

    public function value() : string
    {
        return $this->value;
    }

    public function __destruct()
    {
        $this->wipe();
    }

    public function __clone()
    {
        $this->value = '**********';
    }

    public function __debugInfo() : array
    {
        return [];
    }

    /**
     * @throws SodiumException
     */
    protected function wipe() : void
    {
        sodium_memzero($this->value);
        unset($this->value);
        $this->value = '**********';
    }
}
