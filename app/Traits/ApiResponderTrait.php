<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponderTrait {

    /**
     * Send as json response on JSON API format
     * @param array $data
     * @param null $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data = [], $message = null, $code = Response::HTTP_OK): JsonResponse {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $message = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    protected function noContent(): Response {
        return response()->noContent();
    }

}
