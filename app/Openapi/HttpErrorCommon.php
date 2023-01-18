<?php
declare(strict_types=1);

namespace App\Openapi;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "HttpErrorCommon",
    properties: [
        new OA\Property(property: "status", type: "number"),
        new OA\Property(property: "title", type: "string"),
        new OA\Property(property: "data", type: "array", items: new OA\Items()),
    ]
)]
class HttpErrorCommon
{
}