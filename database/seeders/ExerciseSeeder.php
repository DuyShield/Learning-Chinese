<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // [hanzi của từ, câu hỏi, các đáp án, đáp án đúng]
        $exercises = [
            ['学习', 'Chọn nghĩa đúng của 学习', ['học tập', 'ăn', 'uống', 'yêu'], 'học tập'],
            ['水', 'Chọn nghĩa đúng của 水', ['trà', 'nước', 'sách', 'nhà'], 'nước'],
            ['爱', 'Chọn nghĩa đúng của 爱', ['yêu', 'thích', 'ăn', 'bạn bè'], 'yêu'],
        ];

        foreach ($exercises as [$hanzi, $question, $options, $answer]) {
            $vocabId = DB::table('vocabularies')->where('hanzi', $hanzi)->value('id');

            if (! $vocabId) {
                continue; // chưa có từ vựng tương ứng thì bỏ qua
            }

            DB::table('exercises')->updateOrInsert(
                ['vocabulary_id' => $vocabId, 'question_text' => $question],
                [
                    'skill' => 'VOCAB',
                    'exercise_type' => 'MULTIPLE_CHOICE',
                    'hsk_level' => 'HSK1',
                    'options_json' => json_encode($options, JSON_UNESCAPED_UNICODE),
                    'correct_answer' => $answer,
                    'explanation' => "{$hanzi} có nghĩa là {$answer}.",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
