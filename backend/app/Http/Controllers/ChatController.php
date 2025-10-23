<?php

namespace App\Http\Controllers;

use App\Application\Services\ChatService;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(private ChatService $chatService) {}

    public function index()
    {
        $chats = $this->chatService->getUserChats(auth()->user());
        return ChatResource::collection($chats);
    }

    public function store(Request $request)
    {
        $chat = $this->chatService->createChat($request->all(), auth()->user());
        return new ChatResource($chat);
    }

    public function show($id)
    {
        $chat = $this->chatService->getChatWithMessages($id, auth()->user());
        return new ChatResource($chat);
    }

    public function getMessages($id)
    {
        $messages = $this->chatService->getChatMessages($id, auth()->user());
        return MessageResource::collection($messages);
    }

    public function sendMessage(Request $request, $id)
    {
        $message = $this->chatService->sendMessage($request->all(), $id, auth()->user());
        return new MessageResource($message);
    }
}