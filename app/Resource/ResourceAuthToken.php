<?php
declare(strict_types=1);

namespace App\Resource;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ResourceAuthToken",
    properties: [
        new OA\Property(property: "access_token", type: "string"),
    ],
    type: "object"
)]
class ResourceAuthToken
{
}
