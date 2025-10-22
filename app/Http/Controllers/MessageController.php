<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        $messages = $chat->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return response()->json([
            'messages' => $messages,
            'chat' => $chat
        ]);
    }

    public function store(Request $request, Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        $request->validate([
            'message' => 'required|string|max:1000',
            'type' => 'sometimes|in:text,image,file,system',
            'metadata' => 'sometimes|array',
        ]);

        $user = $request->user();

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'type' => $request->type ?? 'text',
            'metadata' => $request->metadata,
        ]);

        $message->load('user');

        // Broadcast el mensaje a todos los participantes
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'message' => $message,
            'status' => 'Message sent successfully'
        ], 201);
    }

    public function update(Request $request, Message $message): JsonResponse
    {
        $this->authorize('update', $message);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Solo permitir edición dentro de 5 minutos
        if ($message->created_at->diffInMinutes(now()) > 5) {
            return response()->json([
                'message' => 'Message can only be edited within 5 minutes of sending'
            ], 422);
        }

        $message->update([
            'message' => $request->message
        ]);

        $message->load('user');

        return response()->json([
            'message' => $message,
            'status' => 'Message updated successfully'
        ]);
    }

    public function destroy(Message $message): JsonResponse
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }

    public function markAsRead(Chat $chat): JsonResponse
    {
        $user = request()->user();
        $participant = $chat->participants()->where('user_id', $user->id)->first();
        
        if ($participant) {
            $participant->markAsRead();
        }

        return response()->json([
            'message' => 'Messages marked as read'
        ]);
    }
}