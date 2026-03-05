<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'CyberBuddy API',
    description: 'API documentation for the CyberBuddy application.'
)]
#[OA\Server(
    url: '/api',
    description: 'Local API Server'
)]
class OpenApi
{
}
