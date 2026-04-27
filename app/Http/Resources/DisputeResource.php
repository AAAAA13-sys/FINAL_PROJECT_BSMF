<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DisputeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'dispute_number' => $this->dispute_number,
            'order' => new OrderResource($this->whenLoaded('order')),
            'subject' => $this->subject,
            'description' => $this->description,
            'status' => $this->status,
            'messages' => DisputeMessageResource::collection($this->whenLoaded('messages')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
