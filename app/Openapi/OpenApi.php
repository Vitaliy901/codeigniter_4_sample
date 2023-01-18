<?php

namespace App\Openapi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0",
    description: "CodeIgniter 4 sample API swagger documentation",
    title: 'CodeIgniter 4 API'
)]
#[OA\Server(url: "http://localhost/", description: "Local Server")]
#[OA\SecurityScheme(securityScheme: "bearerAuth", type: "http", in: "header", bearerFormat: "JWT", scheme: "bearer")]
class OpenApi
{

}