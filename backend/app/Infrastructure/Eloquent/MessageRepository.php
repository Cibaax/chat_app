<?php

namespace App\Infrastructure\Eloquent;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository
{
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function getChatMessages(int $chatId, User $user): Collection
    {
        return Message::where('chat_id', $chatId)
                     ->with('user')
                     ->orderBy('created_at', 'asc')
                     ->get();
    }
}