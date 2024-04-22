<?php

namespace App\Trait;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    protected function createApiResponse(mixed $data = [], string $message = '', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data
        ]);
    }
}