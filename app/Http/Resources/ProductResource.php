<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'casting_name' => $this->casting_name,
            'year' => $this->year,
            'color' => $this->color,
            'price' => $this->price,
            'original_price' => $this->original_price,
            'stock_quantity' => $this->stock_quantity,
            'stock_status' => $this->stock_status,
            'is_treasure_hunt' => $this->is_treasure_hunt,
            'is_super_treasure_hunt' => $this->is_super_treasure_hunt,
            'is_chase' => $this->is_chase,
            'is_rlc_exclusive' => $this->is_rlc_exclusive,
            'card_condition' => $this->card_condition,
            'main_image' => $this->main_image,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'brand' => $this->whenLoaded('brand', function() {
                return $this->brand->name;
            }),
            'scale' => $this->whenLoaded('scale', function() {
                return $this->scale->name;
            }),
            'series' => $this->whenLoaded('series', function() {
                return $this->series->name;
            }),
            'images' => $this->whenLoaded('images', function() {
                return $this->images->pluck('image_path');
            }),
            'created_at' => $this->created_at,
        ];
    }
}
