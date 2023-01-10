<?php

declare(strict_types=1);

namespace App\Kernel\Http\Request;

use App\Shared\Helpers\ParameterBag;

trait ServerRequestParameterBag
{
    public function attributes() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getAttributes());
    }

    public function cookie() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getCookieParams());
    }

    public function json() : ParameterBag
    {
        return new ParameterBag(json_decode($this->serverRequest->getBody()->getContents(), true));
    }

    public function query() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getQueryParams());
    }

    public function server() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getServerParams());
    }

    public function files() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getUploadedFiles());
    }

    public function parsedBody() : ParameterBag
    {
        return new ParameterBag($this->serverRequest->getParsedBody());
    }

}
