<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'type' => $this->type,
            'metadata' => $this->metadata,
            'is_own' => $this->user_id === $request->user()?->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'chat_id' => $this->chat_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}