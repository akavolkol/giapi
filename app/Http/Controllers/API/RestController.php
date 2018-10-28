<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(title="API Docs", version="0.1"),
 * @OA\Server(
 *     url="/api"
 * ),
 * @OA\Schema(
 *     schema="JWT",
 *     type="string",
 * ),
 * @OA\Schema(
 *     schema="AuthorizationHeader",
 *     type="string",
 * )
 */
class RestController extends Controller
{
    /**
     * @param array $data
     * @param int $status
     * @return JsonResponse
     */
    public function response(array $data, int $status = 200)
    {
        return new JsonResponse($data, $status);
    }
}
