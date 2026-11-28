<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Ngữ pháp của Bài 1, dùng đúng các từ trong VocabularySeeder.
 * Cần đã chạy migration của grammar_points và grammar_examples.
 */
class GrammarSeeder extends Seeder
{
    public function run(): void
    {
        $lessonId = DB::table('lessons')->where('title', LessonSeeder::LESSON_1)->value('id');
        $now = now();

        // [tiêu đề, cấu trúc, giải thích, lưu ý, ví dụ [hanzi, pinyin, nghĩa]]
        $points = [
            ['Câu chữ 是', 'A + 是 + B',
                'Dùng 是 để nối hai danh từ, giới thiệu hoặc khẳng định A là B.',
                'Với tính từ thường không dùng 是 mà dùng 很, ví dụ 他很高.',
                [
                    ['我是学生。', 'Wǒ shì xuésheng.', 'Tôi là học sinh.'],
                    ['他是老师。', 'Tā shì lǎoshī.', 'Anh ấy là giáo viên.'],
                    ['他是我的朋友。', 'Tā shì wǒ de péngyou.', 'Anh ấy là bạn của tôi.'],
                ]],
            ['Phủ định với 不', '不 + động từ / tính từ',
                'Đặt 不 trước động từ hoặc tính từ để phủ định. Với 是 thì nói 不是.',
                'Trước âm thanh 4, 不 đọc thành bú, ví dụ 不是 đọc là bú shì.',
                [
                    ['我不喝茶。', 'Wǒ bù hē chá.', 'Tôi không uống trà.'],
                    ['他不是老师。', 'Tā bú shì lǎoshī.', 'Anh ấy không phải giáo viên.'],
                    ['我不喜欢茶。', 'Wǒ bù xǐhuan chá.', 'Tôi không thích trà.'],
                ]],
            ['Câu hỏi với 吗', 'Câu trần thuật + 吗？',
                'Thêm 吗 vào cuối câu trần thuật để tạo câu hỏi có hoặc không.',
                'Không dùng 吗 cùng với các từ để hỏi như 什么, 谁, 哪儿.',
                [
                    ['你好吗？', 'Nǐ hǎo ma?', 'Bạn khỏe không?'],
                    ['你是学生吗？', 'Nǐ shì xuésheng ma?', 'Bạn là học sinh phải không?'],
                    ['你喝茶吗？', 'Nǐ hē chá ma?', 'Bạn có uống trà không?'],
                ]],
        ];

        foreach ($points as $i => [$title, $structure, $explanation, $note, $examples]) {
            $key = ['lesson_id' => $lessonId, 'title' => $title];

            DB::table('grammar_points')->updateOrInsert($key, [
                'structure' => $structure,
                'explanation' => $explanation,
                'note' => $note,
                'hsk_level' => 'HSK1',
                'order_index' => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $pointId = DB::table('grammar_points')->where($key)->value('id');

            // Xóa ví dụ cũ rồi chèn lại để chạy seed nhiều lần không bị nhân đôi
            DB::table('grammar_examples')->where('grammar_point_id', $pointId)->delete();

            foreach ($examples as $j => [$hanzi, $pinyin, $meaning]) {
                DB::table('grammar_examples')->insert([
                    'grammar_point_id' => $pointId,
                    'hanzi' => $hanzi,
                    'pinyin' => $pinyin,
                    'meaning' => $meaning,
                    'order_index' => $j + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
