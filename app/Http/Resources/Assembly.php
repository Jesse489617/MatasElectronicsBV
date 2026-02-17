<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Assembly extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => new ImageResource($this->image),
            'price' => (float) $this->price,
            // 'price_with_btw' => round($this->price * 1.21, 2),
            'components' => Component::collection(
                $this->whenLoaded('components')
            ),
        ];
    }
}
