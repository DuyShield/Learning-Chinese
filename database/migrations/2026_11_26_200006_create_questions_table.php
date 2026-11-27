<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->enum('section', ['VOCAB', 'LISTENING', 'READING', 'WRITING']);
            $table->enum('question_type', [
                'MULTIPLE_CHOICE', 'TRUE_FALSE', 'FILL_BLANK', 'REORDER', 'WRITING',
            ])->default('MULTIPLE_CHOICE');
            $table->text('passage')->nullable();
            $table->text('prompt');
            $table->string('audio_url')->nullable();
            $table->json('options_json')->nullable();
            $table->text('correct_answer');
            $table->decimal('points', 5, 2)->default(1);
            $table->integer('order_index')->default(0);

            $table->index(['exam_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
