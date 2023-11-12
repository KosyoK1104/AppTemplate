<?php

declare(strict_types=1);

namespace App\Configuration;

use App\Shared\Security\OneTimeReadSecuredString;

final class SqlConfig
{
    public function __construct(
        public readonly OneTimeReadSecuredString $host,
        public readonly OneTimeReadSecuredString $username,
        public readonly OneTimeReadSecuredString $password,
        public readonly OneTimeReadSecuredString $database
    ) {
    }
}
