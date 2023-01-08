<?php

declare(strict_types=1);

namespace App\Configuration;

final class Config
{
    public function __construct(
        public readonly Enviroment $enviroment
    )
    {
    }
}
