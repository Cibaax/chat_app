<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'sometimes|string|max:255',
        ]);

        $users = User::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('id', '!=', $request->user()->id) // Excluir usuario actual
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json([
            'users' => $users
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);

        $users = User::where('name', 'like', "%{$request->query}%")
            ->orWhere('email', 'like', "%{$request->query}%")
            ->where('id', '!=', $request->user()->id)
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json([
            'users' => $users
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'created_at'])
        ]);
    }
}