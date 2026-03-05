<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SwaggerTestController extends Controller
{
    #[OA\Get(
        path: '/test-swagger',
        summary: 'Test Swagger endpoint',
        tags: ['Test'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Swagger is working'
            )
        ]
    )]
    public function index(): JsonResponse
    {
        return response()->json(['message' => 'swagger-ok']);
    }
}
