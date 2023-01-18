<?php

namespace App\Openapi;

use OpenApi\Attributes as OA;

/**
 * Documentation Init
 */
#[OA\Info(
    version: "1.0",
    description: "CodeIgniter 4 sample API swagger documentation",
    title: 'CodeIgniter 4 API'
)]
#[OA\Server(url: "http://localhost/", description: "Local Server")]
#[OA\SecurityScheme(securityScheme: "bearerAuth", type: "http", in: "header", bearerFormat: "JWT", scheme: "bearer")]
/**
 * Headers
 */
#[OA\Parameter(parameter: "Locale", name: "Accept-Langueage", in: "header", allowEmptyValue: true, example: "en-US")]
/**
 * Pagination
 */
#[OA\Parameter(parameter: "Pagination_page", name: "page", in: "query", allowEmptyValue: true, example: "1")]
#[OA\Parameter(parameter: "Pagination_per_page", name: "per_page", in: "query", allowEmptyValue: true, example: "20")]
/**
 * Responses
 */
#[OA\Response(response: 200, description: "Success")]
#[OA\Response(response: 400, description: "Bad Request", content:
    new OA\JsonContent(ref: "#/components/schemas/HttpErrorCommon"))]
#[OA\Response(response: 401, description: "Unauthorized", content:
    new OA\JsonContent(ref: "#/components/schemas/HttpErrorCommon"))]
#[OA\Response(response: 404, description: "Not Found", content:
    new OA\JsonContent(ref: "#/components/schemas/HttpErrorCommon"))]
#[OA\Response(response: 403, description: "Validation error.", content:
    new OA\JsonContent(ref: "#/components/schemas/HttpErrorCommon"))]
#[OA\Response(response: 500, description: "Server error.", content:
    new OA\JsonContent(ref: "#/components/schemas/HttpErrorCommon"))]
class OpenApi
{

}