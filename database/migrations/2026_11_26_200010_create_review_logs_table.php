<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vocabulary_id')->nullable()->constrained('vocabularies')->nullOnDelete();
            $table->foreignId('exercise_id')->nullable()->constrained('exercises')->nullOnDelete();
            $table->enum('skill', ['VOCAB', 'LISTENING', 'SPEAKING', 'READING', 'WRITING']);
            $table->text('user_answer')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->string('audio_url')->nullable();  // bản ghi âm của người học (kỹ năng Nói)
            $table->json('feedback_json')->nullable(); // chi tiết chấm phát âm
            $table->integer('time_spent_seconds')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'skill', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_logs');
    }
};
