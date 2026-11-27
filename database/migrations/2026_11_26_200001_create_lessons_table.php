<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('hsk_level', ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])->nullable();
            $table->integer('order_index')->default(0);
            $table->text('content')->nullable();
            $table->string('audio_url')->nullable(); // file ghi âm bài học
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['hsk_level', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
