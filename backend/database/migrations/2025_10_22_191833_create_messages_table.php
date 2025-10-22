<?php
// database/migrations/2024_01_02_create_messages_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message'); // TEXT en PostgreSQL es mejor para mensajes largos
            $table->enum('type', ['text', 'image', 'file', 'system'])->default('text');
            $table->jsonb('metadata')->nullable(); // JSONB en PostgreSQL es más eficiente
            $table->timestamps();
            
            // Índices optimizados para PostgreSQL
            $table->index(['chat_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};