<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'unread_messages_count' => $this->whenLoaded('participants', function () use ($request) {
                return $request->user()->unreadMessagesCount($this->id);
            }),
            'latest_message' => new MessageResource($this->whenLoaded('latestMessage')),
            'participants' => UserResource::collection($this->whenLoaded('participants')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}