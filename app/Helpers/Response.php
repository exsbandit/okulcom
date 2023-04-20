<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as Status;

class Response
{
    public static function run(mixed $data, bool $status = true): JsonResponse
    {
        if ($status) {
            return (is_string($data)) ? self::successWithMessage($data) : self::success($data);
        }
        return self::fail($data);
    }

    public static function successWithMessage(string $message): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], Status::HTTP_OK);
    }

    public static function success(mixed $data): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $data,
        ], Status::HTTP_OK);
    }

    public static function fail(string|array $errors, $code = Status::HTTP_BAD_REQUEST, $message = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'errors' => $errors,
            'message' => $message
        ], $code);
    }

    public static function failWithMessage(
        string $message,
        array $errors = [],
        $code = Status::HTTP_BAD_REQUEST
    ): JsonResponse {
        if (count($errors)) {
            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => $errors,
            ], $code);
        } else {
            return response()->json([
                'status' => false,
                'message' => $message
            ], $code);
        }
    }

    public static function handlerFail(string $message = '', array $errors = [], int $code = 400, $exception = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $code
        ];

        if ($exception !== null) {
            $response['exception'] = $exception->getMessage();
            // $response['trace'] = $exception->getTraceAsString();
        }

        return response()->json($response, $code);
    }
}
