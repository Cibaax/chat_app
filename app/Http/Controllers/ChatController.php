<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $chats = $user->chats()
            ->with(['latestMessage', 'participants'])
            ->withCount(['messages as unread_messages_count' => function($query) use ($user) {
                $query->where('created_at', '>', function($subQuery) use ($user) {
                    $subQuery->select('last_read_at')
                            ->from('participants')
                            ->whereColumn('chat_id', 'chats.id')
                            ->where('user_id', $user->id);
                });
            }])
            ->orderByDesc(
                Chat::select('created_at')
                    ->from('messages')
                    ->whereColumn('chat_id', 'chats.id')
                    ->latest()
                    ->limit(1)
            )
            ->get();

        return response()->json([
            'chats' => $chats
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:private,group',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $user = $request->user();

        // Para chats privados, verificar si ya existe
        if ($request->type === 'private' && count($request->user_ids) === 1) {
            $existingChat = $user->chats()
                ->where('type', 'private')
                ->whereHas('participants', function ($query) use ($request) {
                    $query->where('user_id', $request->user_ids[0]);
                })
                ->first();

            if ($existingChat) {
                return response()->json([
                    'chat' => $existingChat,
                    'message' => 'Existing private chat found'
                ]);
            }
        }

        $chat = Chat::create([
            'name' => $request->name,
            'type' => $request->type,
            'created_by' => $user->id,
        ]);

        // Agregar participantes
        $chat->addParticipant($user, 'admin');
        foreach ($request->user_ids as $userId) {
            if ($userId != $user->id) {
                $chat->addParticipant(User::find($userId));
            }
        }

        $chat->load(['participants', 'latestMessage']);

        return response()->json([
            'chat' => $chat,
            'message' => 'Chat created successfully'
        ], 201);
    }

    public function show(Chat $chat): JsonResponse
    {
        $this->authorize('view', $chat);

        // Marcar mensajes como leídos
        $user = request()->user();
        $participant = $chat->participants()->where('user_id', $user->id)->first();
        if ($participant) {
            $participant->markAsRead();
        }

        $chat->load(['participants', 'messages.user']);

        return response()->json([
            'chat' => $chat
        ]);
    }

    public function addParticipant(Request $request, Chat $chat): JsonResponse
    {
        $this->authorize('update', $chat);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'sometimes|in:admin,member',
        ]);

        $chat->addParticipant(
            User::find($request->user_id),
            $request->role ?? 'member'
        );

        return response()->json([
            'message' => 'Participant added successfully'
        ]);
    }

    public function removeParticipant(Chat $chat, User $user): JsonResponse
    {
        $this->authorize('update', $chat);

        $chat->participants()->detach($user->id);

        return response()->json([
            'message' => 'Participant removed successfully'
        ]);
    }

    public function update(Chat $chat, Request $request): JsonResponse
    {
        $this->authorize('update', $chat);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $chat->update($request->only(['name', 'is_active']));

        return response()->json([
            'chat' => $chat,
            'message' => 'Chat updated successfully'
        ]);
    }
}