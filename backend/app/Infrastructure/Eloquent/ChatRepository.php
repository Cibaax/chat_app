<?php

namespace App\Infrastructure\Eloquent;

use App\Models\Chat;
use App\Models\User;
use App\Domain\Repositories\ChatRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository implements ChatRepositoryInterface
{
    public function create(array $data): Chat
    {
        return Chat::create($data);
    }

    public function getUserChats(User $user): Collection
    {
        return Chat::where('user_id', $user->id)
                  ->with(['messages.user'])
                  ->orderBy('updated_at', 'desc')
                  ->get();
    }

    public function getChatWithMessages(int $chatId, User $user): ?Chat
    {
        return Chat::where('id', $chatId)
                  ->where('user_id', $user->id)
                  ->with(['messages.user'])
                  ->first();
    }

    public function findById(int $chatId): ?Chat
    {
        return Chat::find($chatId);
    }

    public function update(Chat $chat, array $data): bool
    {
        return $chat->update($data);
    }
}