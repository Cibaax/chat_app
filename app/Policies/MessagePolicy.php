<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function update(User $user, Message $message): bool
    {
        return $message->user_id === $user->id && 
               $message->created_at->diffInMinutes(now()) < 5;
    }

    public function delete(User $user, Message $message): bool
    {
        return $message->user_id === $user->id || 
               $message->chat->created_by === $user->id;
    }
}