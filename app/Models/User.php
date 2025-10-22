<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ==================== NUEVAS RELACIONES PARA EL CHAT ====================

    /**
     * Chats where the user is a participant
     */
    public function chats(): BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'participants')
                    ->withPivot('role', 'joined_at', 'last_read_at')
                    ->withTimestamps();
    }

    /**
     * Messages sent by the user
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Participant records for the user
     */
    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    /**
     * Chats created by the user
     */
    public function createdChats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    /**
     * Check if user is admin in a specific chat
     */
    public function isAdminInChat($chatId): bool
    {
        return $this->participants()
                    ->where('chat_id', $chatId)
                    ->where('role', 'admin')
                    ->exists();
    }

    /**
     * Get unread messages count for a chat
     */
    public function unreadMessagesCount($chatId): int
    {
        $lastRead = $this->participants()
                        ->where('chat_id', $chatId)
                        ->value('last_read_at');

        if (!$lastRead) {
            return Message::where('chat_id', $chatId)->count();
        }

        return Message::where('chat_id', $chatId)
                     ->where('created_at', '>', $lastRead)
                     ->count();
    }
}