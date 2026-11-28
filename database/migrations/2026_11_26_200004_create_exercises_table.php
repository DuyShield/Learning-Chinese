<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            // Bài tập gắn với một từ (ôn từ vựng) hoặc một bài học (bài Đọc, bài Nghe)
            $table->foreignId('vocabulary_id')->nullable()->constrained('vocabularies')->cascadeOnDelete();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->cascadeOnDelete();
            $table->enum('skill', ['VOCAB', 'LISTENING', 'SPEAKING', 'READING', 'WRITING']);
            $table->enum('exercise_type', [
                'MULTIPLE_CHOICE', 'TRUE_FALSE', 'FILL_BLANK', 'TRANSLATION', 'REORDER', 'SPEAK', 'WRITE',
            ]);
            $table->enum('hsk_level', ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])->nullable();
            $table->text('passage')->nullable(); // đoạn văn hoặc hội thoại cho bài Đọc, Nghe
            $table->text('question_text');
            $table->string('audio_url')->nullable();
            $table->json('options_json')->nullable();
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['vocabulary_id', 'skill']);
            $table->index(['lesson_id', 'skill']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
