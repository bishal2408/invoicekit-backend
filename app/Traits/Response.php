<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait Response
{
    /**
     * sendResponse
     *
     * @param  string  $message
     * @param  int  $status
     * @param  array<mixed>  $headers
     * @return JsonResponse
     */
    private function sendResponse($message, $status = 200, $headers = [])
    {
        return response()->json([
            'message' => $message,
        ], $status, $headers);
    }

    /**
     * sendResponseWithData
     *
     * @param  array<string,mixed>  $data
     * @param  string|null  $message
     * @param  int  $status
     * @param  array<mixed>  $headers
     * @return JsonResponse
     */
    private function sendResponseWithData(array $data, $message = null, $status = 200, $headers = [])
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status, $headers);
    }

    /**
     * successResponse
     *
     * @param  string  $message
     * @return JsonResponse
     */
    private function successResponse($message)
    {
        return response()->json([
            'message' => $message,
        ], 200);
    }

    /**
     * errorResponse
     *
     * @param  string  $message
     * @return JsonResponse
     */
    private function errorResponse($message)
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }

    /**
     * errorResponseWithData
     *
     * @param  array<string,mixed>  $data
     * @param  string|null  $message
     * @param  int  $status
     * @param  array<mixed>  $headers
     * @return JsonResponse
     */
    private function errorResponseWithData(array $data, $message = null, $status = 422, $headers = [])
    {
        return response()->json([
            'message' => $message,
            'errors' => $data,
        ], $status, $headers);
    }
}
