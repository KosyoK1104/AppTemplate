<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\RestResponseFactory;
use App\Shared\Helpers\ObjectToArrayTransformer;
use Psr\Http\Message\ResponseInterface;

abstract class RestController extends Controller
{
    public readonly RestResponseFactory $responseFactory;

    public function __setResponseFactory(RestResponseFactory $responseFactory) : void
    {
        $this->responseFactory = $responseFactory;
    }

    public function respond(mixed $data, $status = 200) : ResponseInterface
    {
        if (is_object($data)) {
            $data = ObjectToArrayTransformer::transform($data);
        }
        if (is_int($data)) {
            $data = (string) $data;
        }
        if (is_null($data)) {
            $data = [];
        }
        return $this->responseFactory->success(json_encode(['data' => $data]), $status);
    }
}
