<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->nullOnDelete();

            // utf8mb4_bin để phân biệt thanh điệu (hǎo khác hào), tránh chặn nhầm từ đa âm
            $table->string('hanzi', 50)->collation('utf8mb4_bin');
            $table->string('pinyin', 100)->collation('utf8mb4_bin');

            $table->string('meaning');
            $table->string('part_of_speech', 30)->nullable();
            $table->enum('hsk_level', ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])->nullable();
            $table->string('audio_url')->nullable();          // thầy ghi âm từ
            $table->string('example_hanzi')->nullable();
            $table->string('example_pinyin')->nullable();
            $table->string('example_meaning')->nullable();
            $table->string('example_audio_url')->nullable();  // thầy ghi âm câu ví dụ
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Khóa upsert khi import Excel, đồng thời phục vụ tìm theo hanzi
            $table->unique(['hanzi', 'pinyin'], 'u_vocab_hanzi_pinyin');
            $table->index('hsk_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
