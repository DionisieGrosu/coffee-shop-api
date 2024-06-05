<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coffee' => new CoffeeResource($this->coffee),
            'size' => new SizeResource($this->size),
            'qt' => $this->qt,
        ];
    }
}
