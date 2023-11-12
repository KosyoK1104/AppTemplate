<?php

declare(strict_types=1);

namespace App\Configuration;

enum Enviroment: string
{
    case LOCAL = 'local';
    case DEV = 'dev';
    case PROD = 'prod';

    public function isLocal() : bool
    {
        return $this === self::LOCAL;
    }

    public function isDev() : bool
    {
        return $this === self::DEV;
    }

    public function isProd() : bool
    {
        return $this === self::PROD;
    }
}
