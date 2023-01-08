<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Request\Request;

abstract class Controller
{
    public readonly Request $request;

    /** @noinspection MagicMethodsValidityInspection */
    public function __setRequest(Request $request) : void
    {
        $this->request = $request;
    }
}
