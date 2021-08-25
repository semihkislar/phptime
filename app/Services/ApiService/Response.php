<?php

namespace App\Services\ApiService;

use Illuminate\Http\JsonResponse;

class Response
{
    /**
     * @param $errorMessage
     * @param int $errorCode
     *
     * @return JsonResponse
     */
    public static function error($errorMessages)
    {
        return response()->json([
            'status' => 'failure',
            'errors' => $errorMessages,
        ], 200);
    }

    /**
     * @param $data
     *
     * @return JsonResponse
     *
     * Returns Success Response
     */
    public static function success($data = null)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

}
