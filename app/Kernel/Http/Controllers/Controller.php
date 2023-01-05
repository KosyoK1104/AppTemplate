<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Request\Request;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    public readonly Request $request;

    public function __setRequest(Request $request) : void
    {
        $this->request = $request;
    }

    abstract public function respond(array $data, $status = 200) : ResponseInterface;

}
