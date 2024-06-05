<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoffeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'img' => $this->img ? (Storage::disk('public')->exists($this->img) ? asset('storage/'.$this->img) : '') : '',
            'topics' => $this->topics,
            'rate' => $this->reviews->average('rate'),
            'rate_exists' => $this->reviews->where('user_id', Auth::user()->id)->where('coffee_id', $this->id)->count() > 0,
            'rate_count' => $this->reviews->where('coffee_id', $this->id)->count(),
            'sizes' => SizeResource::collection($this->activeSizes),
        ];
    }
}
