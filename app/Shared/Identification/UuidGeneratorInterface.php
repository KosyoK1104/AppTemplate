<?php

declare(strict_types=1);

namespace App\Shared\Identification;

interface UuidGeneratorInterface
{
    public function generate(string $class) : Uuid;
}
