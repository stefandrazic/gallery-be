<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
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
            'img_urls' => $this->img_urls,
            'created_at' => $this->created_at,
            'author' => [
                'id' => $this->author->id,
                'first_name' => $this->author->first_name,
                'last' => $this->author->last_name,
            ]
        ];
    }
}
