<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (! $this->resource) {
            return [];
        }

        $pathInfo = pathinfo($this->resource);

        return [
            'main' => asset('storage/'.$pathInfo['dirname'].'/400_'.$pathInfo['basename']),
            'icon' => asset('storage/'.$pathInfo['dirname'].'/50_'.$pathInfo['basename']),
        ];
    }
}
