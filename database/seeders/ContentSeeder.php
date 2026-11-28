<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Bài học, từ vựng, ngữ pháp mẫu. Chạy lại nhiều lần không bị nhân đôi dữ liệu.
 * Cấp HSK gán theo bài, chỉ là dữ liệu mẫu để dựng giao diện.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        foreach ($this->lessons() as $order => $lesson) {
            $key = ['title' => $lesson['title'], 'hsk_level' => $lesson['hsk']];

            DB::table('lessons')->updateOrInsert($key, [
                'description' => $lesson['description'],
                'order_index' => $lesson['order'],
                'content' => $lesson['content'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $lessonId = DB::table('lessons')->where($key)->value('id');

            // Từ vựng: [hanzi, pinyin, nghĩa, từ loại, ví dụ hán, ví dụ pinyin, ví dụ nghĩa]
            foreach ($lesson['vocab'] as [$hanzi, $pinyin, $meaning, $pos, $exHanzi, $exPinyin, $exMeaning]) {
                DB::table('vocabularies')->updateOrInsert(
                    ['hanzi' => $hanzi, 'pinyin' => $pinyin],
                    [
                        'lesson_id' => $lessonId,
                        'meaning' => $meaning,
                        'part_of_speech' => $pos,
                        'hsk_level' => $lesson['hsk'],
                        'example_hanzi' => $exHanzi,
                        'example_pinyin' => $exPinyin,
                        'example_meaning' => $exMeaning,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }

            // Ngữ pháp: [tiêu đề, cấu trúc, giải thích, lưu ý, [ví dụ: hán, pinyin, nghĩa]]
            foreach ($lesson['grammar'] as $i => [$title, $structure, $explanation, $note, $examples]) {
                $gKey = ['lesson_id' => $lessonId, 'title' => $title];

                DB::table('grammar_points')->updateOrInsert($gKey, [
                    'structure' => $structure,
                    'explanation' => $explanation,
                    'note' => $note,
                    'hsk_level' => $lesson['hsk'],
                    'order_index' => $i + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $gId = DB::table('grammar_points')->where($gKey)->value('id');

                DB::table('grammar_examples')->where('grammar_point_id', $gId)->delete();
                foreach ($examples as $j => [$hanzi, $pinyin, $meaning]) {
                    DB::table('grammar_examples')->insert([
                        'grammar_point_id' => $gId,
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

    private function lessons(): array
    {
        return [
            [
                'title' => 'Chào hỏi cơ bản', 'hsk' => 'HSK1', 'order' => 1,
                'description' => 'Những câu chào hỏi, cảm ơn, xin lỗi dùng hằng ngày.',
                'content' => 'Trong bài này bạn học cách chào hỏi, cảm ơn, xin lỗi và tạm biệt bằng tiếng Trung.',
                'vocab' => [
                    ['你好', 'nǐ hǎo', 'xin chào', 'cụm từ', '你好，我是小明。', 'Nǐ hǎo, wǒ shì Xiǎo Míng.', 'Xin chào, tôi là Tiểu Minh.'],
                    ['谢谢', 'xièxie', 'cảm ơn', 'động từ', '谢谢你的帮助。', 'Xièxie nǐ de bāngzhù.', 'Cảm ơn sự giúp đỡ của bạn.'],
                    ['不客气', 'bú kèqi', 'đừng khách sáo', 'cụm từ', '谢谢！不客气。', 'Xièxie! Bú kèqi.', 'Cảm ơn! Đừng khách sáo.'],
                    ['再见', 'zàijiàn', 'tạm biệt', 'động từ', '明天见，再见！', 'Míngtiān jiàn, zàijiàn!', 'Ngày mai gặp, tạm biệt!'],
                    ['对不起', 'duìbuqǐ', 'xin lỗi', 'động từ', '对不起，我来晚了。', 'Duìbuqǐ, wǒ lái wǎn le.', 'Xin lỗi, tôi đến muộn.'],
                    ['没关系', 'méi guānxi', 'không sao', 'cụm từ', '没关系，不要紧。', 'Méi guānxi, bú yàojǐn.', 'Không sao, không quan trọng.'],
                ],
                'grammar' => [
                    ['Câu hỏi với 吗', 'Câu trần thuật + 吗？',
                        'Thêm 吗 vào cuối câu trần thuật để biến thành câu hỏi có hoặc không.',
                        'Không dùng 吗 cùng với các từ để hỏi như 什么, 谁, 哪儿.',
                        [
                            ['你好吗？', 'Nǐ hǎo ma?', 'Bạn khỏe không?'],
                            ['你是学生吗？', 'Nǐ shì xuésheng ma?', 'Bạn là học sinh phải không?'],
                            ['你喜欢茶吗？', 'Nǐ xǐhuan chá ma?', 'Bạn thích trà không?'],
                        ]],
                ],
            ],
            [
                'title' => 'Giới thiệu bản thân', 'hsk' => 'HSK1', 'order' => 2,
                'description' => 'Nói tên, nghề nghiệp và quốc tịch của mình.',
                'content' => 'Bài này giúp bạn tự giới thiệu: tên là gì, làm nghề gì, là người nước nào.',
                'vocab' => [
                    ['我', 'wǒ', 'tôi', 'đại từ', '我是学生。', 'Wǒ shì xuésheng.', 'Tôi là học sinh.'],
                    ['你', 'nǐ', 'bạn', 'đại từ', '你是老师吗？', 'Nǐ shì lǎoshī ma?', 'Bạn là giáo viên phải không?'],
                    ['是', 'shì', 'là', 'động từ', '他是我的朋友。', 'Tā shì wǒ de péngyou.', 'Anh ấy là bạn của tôi.'],
                    ['学生', 'xuésheng', 'học sinh', 'danh từ', '我是越南学生。', 'Wǒ shì Yuènán xuésheng.', 'Tôi là học sinh Việt Nam.'],
                    ['老师', 'lǎoshī', 'giáo viên', 'danh từ', '王老师很好。', 'Wáng lǎoshī hěn hǎo.', 'Thầy Vương rất tốt.'],
                    ['叫', 'jiào', 'tên là, gọi', 'động từ', '我叫小明。', 'Wǒ jiào Xiǎo Míng.', 'Tôi tên là Tiểu Minh.'],
                    ['名字', 'míngzi', 'tên', 'danh từ', '你叫什么名字？', 'Nǐ jiào shénme míngzi?', 'Bạn tên là gì?'],
                    ['人', 'rén', 'người', 'danh từ', '我是越南人。', 'Wǒ shì Yuènán rén.', 'Tôi là người Việt Nam.'],
                ],
                'grammar' => [
                    ['Câu chữ 是', 'A + 是 + B',
                        'Dùng 是 để nối hai danh từ, giới thiệu hoặc khẳng định A là B. Phủ định bằng 不是.',
                        'Với tính từ, thường không dùng 是 mà dùng 很, ví dụ 他很高.',
                        [
                            ['我是学生。', 'Wǒ shì xuésheng.', 'Tôi là học sinh.'],
                            ['他不是老师。', 'Tā bú shì lǎoshī.', 'Anh ấy không phải giáo viên.'],
                            ['你是越南人吗？', 'Nǐ shì Yuènán rén ma?', 'Bạn là người Việt Nam phải không?'],
                        ]],
                    ['Hỏi và nói tên với 叫', '我叫 + tên / 你叫什么名字？',
                        'Dùng 叫 để nói tên mình hoặc hỏi tên người khác.',
                        'Khi hỏi tên, dùng 什么名字 chứ không dùng 吗.',
                        [
                            ['我叫小明。', 'Wǒ jiào Xiǎo Míng.', 'Tôi tên là Tiểu Minh.'],
                            ['你叫什么名字？', 'Nǐ jiào shénme míngzi?', 'Bạn tên là gì?'],
                            ['她叫王丽。', 'Tā jiào Wáng Lì.', 'Cô ấy tên là Vương Lệ.'],
                        ]],
                ],
            ],
            [
                'title' => 'Số đếm và thời gian', 'hsk' => 'HSK1', 'order' => 3,
                'description' => 'Đếm số, hỏi giờ và nói các ngày trong tuần.',
                'content' => 'Học các số từ một đến mười, cách nói giờ và các từ chỉ thời gian cơ bản.',
                'vocab' => [
                    ['一', 'yī', 'một', 'số từ', '我有一个哥哥。', 'Wǒ yǒu yí ge gēge.', 'Tôi có một anh trai.'],
                    ['三', 'sān', 'ba', 'số từ', '现在三点。', 'Xiànzài sān diǎn.', 'Bây giờ là ba giờ.'],
                    ['十', 'shí', 'mười', 'số từ', '我有十本书。', 'Wǒ yǒu shí běn shū.', 'Tôi có mười quyển sách.'],
                    ['今天', 'jīntiān', 'hôm nay', 'danh từ', '今天星期一。', 'Jīntiān xīngqīyī.', 'Hôm nay là thứ Hai.'],
                    ['明天', 'míngtiān', 'ngày mai', 'danh từ', '明天你有空吗？', 'Míngtiān nǐ yǒu kòng ma?', 'Ngày mai bạn có rảnh không?'],
                    ['点', 'diǎn', 'giờ', 'lượng từ', '现在八点。', 'Xiànzài bā diǎn.', 'Bây giờ là tám giờ.'],
                    ['现在', 'xiànzài', 'bây giờ', 'danh từ', '现在几点？', 'Xiànzài jǐ diǎn?', 'Bây giờ là mấy giờ?'],
                ],
                'grammar' => [
                    ['Nói giờ với 点', 'Số + 点 (+ số + 分)',
                        'Dùng 点 để nói giờ, thêm 分 để nói phút. Hỏi giờ dùng 几点.',
                        'Giờ hai nói là 两点, không nói 二点.',
                        [
                            ['现在八点。', 'Xiànzài bā diǎn.', 'Bây giờ là tám giờ.'],
                            ['我七点起床。', 'Wǒ qī diǎn qǐchuáng.', 'Tôi thức dậy lúc bảy giờ.'],
                            ['现在两点十分。', 'Xiànzài liǎng diǎn shí fēn.', 'Bây giờ là hai giờ mười phút.'],
                        ]],
                ],
            ],
            [
                'title' => 'Mua sắm và hỏi giá', 'hsk' => 'HSK2', 'order' => 1,
                'description' => 'Hỏi giá, mặc cả và so sánh khi đi mua sắm.',
                'content' => 'Bài này học các mẫu câu khi đi mua sắm: hỏi giá, nhận xét đắt rẻ và so sánh hai món đồ.',
                'vocab' => [
                    ['多少', 'duōshao', 'bao nhiêu', 'đại từ', '这个多少钱？', 'Zhège duōshao qián?', 'Cái này bao nhiêu tiền?'],
                    ['钱', 'qián', 'tiền', 'danh từ', '我没有钱。', 'Wǒ méiyǒu qián.', 'Tôi không có tiền.'],
                    ['便宜', 'piányi', 'rẻ', 'tính từ', '这件衣服很便宜。', 'Zhè jiàn yīfu hěn piányi.', 'Bộ quần áo này rất rẻ.'],
                    ['贵', 'guì', 'đắt', 'tính từ', '这个太贵了。', 'Zhège tài guì le.', 'Cái này đắt quá.'],
                    ['买', 'mǎi', 'mua', 'động từ', '我想买一本书。', 'Wǒ xiǎng mǎi yì běn shū.', 'Tôi muốn mua một quyển sách.'],
                    ['商店', 'shāngdiàn', 'cửa hàng', 'danh từ', '商店在哪儿？', 'Shāngdiàn zài nǎr?', 'Cửa hàng ở đâu?'],
                ],
                'grammar' => [
                    ['Câu so sánh với 比', 'A + 比 + B + tính từ',
                        'Dùng 比 để so sánh hai đối tượng, A hơn B ở một tính chất nào đó.',
                        'Không đặt 很 trước tính từ trong câu so sánh. Muốn nhấn mạnh thì dùng 更 hoặc 还.',
                        [
                            ['这个比那个贵。', 'Zhège bǐ nàge guì.', 'Cái này đắt hơn cái kia.'],
                            ['他比我高。', 'Tā bǐ wǒ gāo.', 'Anh ấy cao hơn tôi.'],
                            ['今天比昨天冷。', 'Jīntiān bǐ zuótiān lěng.', 'Hôm nay lạnh hơn hôm qua.'],
                        ]],
                    ['Trợ từ 了 chỉ hoàn thành', 'S + V + 了 + O',
                        'Đặt 了 sau động từ để diễn tả hành động đã xảy ra hoặc đã hoàn thành.',
                        'Phủ định dùng 没(有) và bỏ 了, ví dụ 我没买书.',
                        [
                            ['我买了一本书。', 'Wǒ mǎi le yì běn shū.', 'Tôi đã mua một quyển sách.'],
                            ['他吃了饭。', 'Tā chī le fàn.', 'Anh ấy đã ăn cơm.'],
                            ['我们看了电影。', 'Wǒmen kàn le diànyǐng.', 'Chúng tôi đã xem phim.'],
                        ]],
                ],
            ],
        ];
    }
}
