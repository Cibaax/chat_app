<?php

namespace App\Application\Services;

use App\Events\MessageSent;
use App\Events\UserJoinedChat;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Infrastructure\Eloquent\ChatRepository;
use App\Infrastructure\Eloquent\MessageRepository;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{
    public function __construct(
        private ChatRepository $chatRepository,
        private MessageRepository $messageRepository
    ) {}

    /**
     * Crear un nuevo chat
     */
    public function createChat(array $data, User $user): Chat
    {
        $chat = $this->chatRepository->create([
            'name' => $data['name'],
            'user_id' => $user->id
        ]);

        // Disparar evento de usuario uniéndose al chat
        event(new UserJoinedChat($user, $chat));

        return $chat;
    }

    /**
     * Obtener todos los chats del usuario
     */
    public function getUserChats(User $user): Collection
    {
        return $this->chatRepository->getUserChats($user);
    }

    /**
     * Obtener un chat específico con sus mensajes
     */
    public function getChatWithMessages(int $chatId, User $user): ?Chat
    {
        return $this->chatRepository->getChatWithMessages($chatId, $user);
    }

    /**
     * Enviar un mensaje a un chat
     */
    public function sendMessage(array $data, int $chatId, User $user): Message
    {
        $message = $this->messageRepository->create([
            'content' => $data['content'],
            'chat_id' => $chatId,
            'user_id' => $user->id
        ]);

        // Disparar evento de mensaje enviado
        event(new MessageSent($message));

        return $message;
    }

    /**
     * Obtener mensajes de un chat
     */
    public function getChatMessages(int $chatId, User $user): Collection
    {
        return $this->messageRepository->getChatMessages($chatId, $user);
    }
}