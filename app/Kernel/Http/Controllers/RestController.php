<?php

declare(strict_types=1);

namespace App\Kernel\Http\Controllers;

use App\Kernel\Http\Response\RestResponseFactory;
use App\Shared\Helpers\ObjectToArrayTransformer;
use App\Shared\Pagination\PaginatedCollection;
use Psr\Http\Message\ResponseInterface;

abstract class RestController extends Controller
{
    public readonly RestResponseFactory $responseFactory;

    /** @noinspection MagicMethodsValidityInspection */
    public function __setResponseFactory(RestResponseFactory $responseFactory) : void
    {
        $this->responseFactory = $responseFactory;
    }

    public function respond(mixed $data, int $status = 200) : ResponseInterface
    {
        if ($data instanceof PaginatedCollection) {
            return $this->respondSuccess(
                [
                    'data'       => ObjectToArrayTransformer::transform($data->items()),
                    'pagination' => $data->pagination(),
                ],
                $status
            );
        }
        if (is_object($data)) {
            $data = ObjectToArrayTransformer::transform($data);
        }
        if (is_int($data)) {
            $data = (string) $data;
        }
        if (is_null($data)) {
            $data = [];
        }
        return $this->respondSuccess(['data' => $data], $status);
    }

    protected function respondSuccess(array $data, int $status) : ResponseInterface
    {
        return $this->responseFactory->success(json_encode($data), $status);
    }
}
