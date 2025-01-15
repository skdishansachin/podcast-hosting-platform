<?php

namespace App\Http\Resources\Podcast;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PodCastCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
