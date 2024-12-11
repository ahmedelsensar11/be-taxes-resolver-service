<?php
namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseHelper
{
    public const DONE = 'Done';
    public const ERROR = 'Sorry Something Went Wrong';

    public static function success(string $message = self::DONE, mixed $data = [], int $code = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error(string $message = self::ERROR, array $data = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}