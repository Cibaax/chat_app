<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function view(User $user, Chat $chat): bool
    {
        return $chat->participants()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Chat $chat): bool
    {
        return $chat->participants()
                    ->where('user_id', $user->id)
                    ->where('role', 'admin')
                    ->exists();
    }

    public function delete(User $user, Chat $chat): bool
    {
        return $chat->created_by === $user->id;
    }
}