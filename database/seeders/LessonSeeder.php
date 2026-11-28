<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    /** Các seeder khác tìm bài học theo tiêu đề này, nên dùng chung một hằng số. */
    public const LESSON_1 = 'Bài 1: Chào hỏi và giới thiệu';

    public function run(): void
    {
        $now = now();

        DB::table('lessons')->updateOrInsert(
            ['title' => self::LESSON_1],
            [
                'description' => 'Các từ cơ bản để chào hỏi, giới thiệu bản thân.',
                'hsk_level' => 'HSK1',
                'order_index' => 1,
                'content' => 'Nội dung bài học mẫu.',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
