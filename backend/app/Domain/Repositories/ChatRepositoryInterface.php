<?php

namespace App\Domain\Repositories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ChatRepositoryInterface
{
    public function create(array $data): Chat;
    public function getUserChats(User $user): Collection;
    public function getChatWithMessages(int $chatId, User $user): ?Chat;
    public function findById(int $chatId): ?Chat;
}