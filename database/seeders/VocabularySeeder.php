<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocabularySeeder extends Seeder
{
    public function run(): void
    {
        $lessonId = DB::table('lessons')->where('title', LessonSeeder::LESSON_1)->value('id');
        $now = now();

        // [hanzi, pinyin, nghĩa, từ loại, ví dụ hanzi, ví dụ pinyin, ví dụ nghĩa]
        $words = [
            ['你', 'nǐ', 'bạn', 'đại từ', '你好吗？', 'Nǐ hǎo ma?', 'Bạn khỏe không?'],
            ['我', 'wǒ', 'tôi', 'đại từ', '我是学生。', 'Wǒ shì xuésheng.', 'Tôi là học sinh.'],
            ['是', 'shì', 'là', 'động từ', '他是老师。', 'Tā shì lǎoshī.', 'Anh ấy là giáo viên.'],
            ['不', 'bù', 'không', 'phó từ', '我不喝茶。', 'Wǒ bù hē chá.', 'Tôi không uống trà.'],
            ['好', 'hǎo', 'tốt, khỏe', 'tính từ', '这本书很好。', 'Zhè běn shū hěn hǎo.', 'Cuốn sách này rất hay.'],
            ['谢谢', 'xièxie', 'cảm ơn', 'động từ', '谢谢你。', 'Xièxie nǐ.', 'Cảm ơn bạn.'],
            ['再见', 'zàijiàn', 'tạm biệt', 'động từ', '再见，老师！', 'Zàijiàn, lǎoshī!', 'Tạm biệt thầy/cô!'],
            ['老师', 'lǎoshī', 'giáo viên', 'danh từ', '老师好！', 'Lǎoshī hǎo!', 'Chào thầy/cô!'],
            ['学生', 'xuésheng', 'học sinh', 'danh từ', '他是学生。', 'Tā shì xuésheng.', 'Anh ấy là học sinh.'],
            ['朋友', 'péngyou', 'bạn bè', 'danh từ', '他是我的朋友。', 'Tā shì wǒ de péngyou.', 'Anh ấy là bạn của tôi.'],
            ['喜欢', 'xǐhuan', 'thích', 'động từ', '我喜欢学习汉语。', 'Wǒ xǐhuan xuéxí hànyǔ.', 'Tôi thích học tiếng Trung.'],
            ['学习', 'xuéxí', 'học tập', 'động từ', '我学习汉语。', 'Wǒ xuéxí hànyǔ.', 'Tôi học tiếng Trung.'],
            ['汉语', 'hànyǔ', 'tiếng Trung', 'danh từ', '你说汉语吗？', 'Nǐ shuō hànyǔ ma?', 'Bạn nói tiếng Trung không?'],
            ['水', 'shuǐ', 'nước', 'danh từ', '我喝水。', 'Wǒ hē shuǐ.', 'Tôi uống nước.'],
            ['茶', 'chá', 'trà', 'danh từ', '请喝茶。', 'Qǐng hē chá.', 'Mời uống trà.'],
            ['吃', 'chī', 'ăn', 'động từ', '你吃饭了吗？', 'Nǐ chī fàn le ma?', 'Bạn ăn cơm chưa?'],
            ['喝', 'hē', 'uống', 'động từ', '我想喝茶。', 'Wǒ xiǎng hē chá.', 'Tôi muốn uống trà.'],
            ['爱', 'ài', 'yêu', 'động từ', '我爱我的家。', 'Wǒ ài wǒ de jiā.', 'Tôi yêu gia đình tôi.'],
            ['家', 'jiā', 'nhà, gia đình', 'danh từ', '我家有四个人。', 'Wǒ jiā yǒu sì ge rén.', 'Nhà tôi có bốn người.'],
            ['书', 'shū', 'sách', 'danh từ', '这是我的书。', 'Zhè shì wǒ de shū.', 'Đây là sách của tôi.'],
        ];

        $rows = array_map(fn ($w) => [
            'lesson_id' => $lessonId,
            'hanzi' => $w[0],
            'pinyin' => $w[1],
            'meaning' => $w[2],
            'part_of_speech' => $w[3],
            'hsk_level' => 'HSK1',
            'example_hanzi' => $w[4],
            'example_pinyin' => $w[5],
            'example_meaning' => $w[6],
            'created_at' => $now,
            'updated_at' => $now,
        ], $words);

        // Khóa (hanzi, pinyin) là khóa duy nhất, chạy seed nhiều lần không bị trùng
        DB::table('vocabularies')->upsert(
            $rows,
            ['hanzi', 'pinyin'],
            ['lesson_id', 'meaning', 'part_of_speech', 'hsk_level', 'example_hanzi', 'example_pinyin', 'example_meaning', 'updated_at']
        );
    }
}
