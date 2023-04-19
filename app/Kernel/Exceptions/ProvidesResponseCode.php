<?php

declare(strict_types=1);

namespace App\Kernel\Exceptions;

interface ProvidesResponseCode
{
    public function responseCode() : int;
}
