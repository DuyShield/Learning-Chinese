<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Thứ tự quan trọng: bài học trước, từ vựng sau, bài tập sau cùng
     * (từ vựng cần lesson_id, bài tập cần vocabulary_id).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LessonSeeder::class,
            VocabularySeeder::class,
            ExerciseSeeder::class,
        ]);

        // Bỏ qua nếu chưa chạy migration của 2 bảng ngữ pháp
        if (Schema::hasTable('grammar_points') && Schema::hasTable('grammar_examples')) {
            $this->call(GrammarSeeder::class);
        }
    }
}
