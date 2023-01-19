<?php

namespace App\Resource;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ResourceSuccess",
    type: "array",
    items: new OA\Items(type: "string"),
)]
class ResourceSuccess
{
}
