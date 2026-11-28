<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserGrammarAndProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_database_grammar_and_save_personal_progress(): void
    {
        $user = $this->createUser();
        $grammarPointId = DB::table('grammar_points')->insertGetId([
            'title' => 'Câu chữ 是',
            'structure' => 'A + 是 + B',
            'explanation' => 'Dùng 是 để nối hai danh từ.',
            'hsk_level' => 'HSK1',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('grammar_points')->insert([
            'title' => 'Trạng thái chưa phân cấp',
            'structure' => 'A + B',
            'explanation' => 'Ngữ pháp không gắn cấp HSK vẫn xuất hiện.',
            'hsk_level' => null,
            'order_index' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('grammar_examples')->insert([
            'grammar_point_id' => $grammarPointId,
            'hanzi' => '我是学生。',
            'pinyin' => 'Wǒ shì xuésheng.',
            'meaning' => 'Tôi là học sinh.',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->get(route('grammar.user'))
            ->assertOk()
            ->assertDontSee('hsk-tabs', false)
            ->assertDontSee('PLT Solutions', false);
        $this->assertSame(
            1,
            preg_match('/<script\b[^>]*id="grammarData"[^>]*>(.*?)<\/script>/s', $response->getContent(), $matches),
        );

        $grammarData = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertCount(2, $grammarData);
        $this->assertContains('Câu chữ 是', array_column($grammarData, 'title'));
        $this->assertContains('Trạng thái chưa phân cấp', array_column($grammarData, 'title'));
        $examples = array_merge(...array_column($grammarData, 'examples'));
        $this->assertContains('我是学生。', array_column($examples, 'hanzi'));

        $this->actingAs($user)
            ->postJson(route('grammar.progress.update', $grammarPointId), ['status' => 'dang-hoc'])
            ->assertOk()
            ->assertJson(['status' => 'dang-hoc']);

        $this->assertDatabaseHas('user_grammar_progress', [
            'user_id' => $user->id,
            'grammar_point_id' => $grammarPointId,
            'status' => 'dang-hoc',
        ]);
    }

    public function test_user_can_search_vocabulary_grammar_and_example_sentences(): void
    {
        $user = $this->createUser();
        DB::table('vocabularies')->insert([
            'hanzi' => '是',
            'pinyin' => 'shì',
            'meaning' => 'là, học sinh',
            'example_hanzi' => '我是学生。',
            'example_pinyin' => 'Wǒ shì xuésheng.',
            'example_meaning' => 'Tôi là học sinh.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $grammarPointId = DB::table('grammar_points')->insertGetId([
            'title' => 'Cách dùng 是',
            'structure' => 'A + 是 + B',
            'explanation' => 'Dùng 是 để nối hai danh từ, ví dụ học sinh.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('grammar_examples')->insert([
            'grammar_point_id' => $grammarPointId,
            'hanzi' => '这是学生。',
            'pinyin' => 'Zhè shì xuésheng.',
            'meaning' => 'Đây là học sinh.',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('search.index', ['q' => '是']))
            ->assertOk()
            ->assertJsonCount(1, 'vocabularies')
            ->assertJsonCount(1, 'grammarPoints')
            ->assertJsonCount(1, 'vocabularySentences')
            ->assertJsonCount(1, 'grammarSentences')
            ->assertJsonPath('grammarPoints.0.title', 'Cách dùng 是')
            ->assertJsonPath('vocabularySentences.0.example_hanzi', '我是学生。')
            ->assertJsonPath('grammarSentences.0.hanzi', '这是学生。');

        $this->actingAs($user)
            ->get(route('search.index', ['q' => 'học sinh']))
            ->assertOk()
            ->assertJsonCount(1, 'vocabularies')
            ->assertJsonCount(1, 'grammarPoints')
            ->assertJsonCount(1, 'vocabularySentences')
            ->assertJsonCount(1, 'grammarSentences')
            ->assertJsonPath('vocabularies.0.meaning', 'là, học sinh')
            ->assertJsonPath('grammarPoints.0.title', 'Cách dùng 是');
    }

    public function test_searching_unaccented_pinyin_finds_vocabulary_and_grammar_in_the_sentence(): void
    {
        $user = $this->createUser();
        DB::table('vocabularies')->insert([
            [
                'hanzi' => '你',
                'pinyin' => 'nǐ',
                'meaning' => 'bạn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hanzi' => '好',
                'pinyin' => 'hǎo',
                'meaning' => 'tốt, khỏe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        $grammarPointId = DB::table('grammar_points')->insertGetId([
            'title' => 'Câu hỏi với 吗',
            'structure' => 'Câu trần thuật + 吗？',
            'explanation' => 'Thêm 吗 vào cuối câu để tạo câu hỏi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('grammar_examples')->insert([
            'grammar_point_id' => $grammarPointId,
            'hanzi' => '你好吗？',
            'pinyin' => 'Nǐ hǎo ma?',
            'meaning' => 'Bạn khỏe không?',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('search.index', ['q' => 'ni hao ma']))
            ->assertOk()
            ->assertJsonCount(2, 'vocabularies')
            ->assertJsonFragment(['hanzi' => '你'])
            ->assertJsonFragment(['hanzi' => '好'])
            ->assertJsonCount(1, 'grammarPoints')
            ->assertJsonPath('grammarPoints.0.title', 'Câu hỏi với 吗');
    }

    public function test_global_search_returns_all_matching_vocabulary_and_grammar_records(): void
    {
        $user = $this->createUser();

        for ($index = 1; $index <= 21; $index++) {
            DB::table('vocabularies')->insert([
                'hanzi' => '字'.$index,
                'pinyin' => 'zi'.$index,
                'meaning' => 'needle '.$index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('grammar_points')->insert([
                'title' => 'needle grammar '.$index,
                'explanation' => 'Search result '.$index,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->actingAs($user)
            ->get(route('search.index', ['q' => 'needle']))
            ->assertOk()
            ->assertJsonCount(21, 'vocabularies')
            ->assertJsonCount(21, 'grammarPoints');
    }

    public function test_searching_a_passage_returns_only_vocabulary_words_found_in_database(): void
    {
        $user = $this->createUser();
        DB::table('vocabularies')->insert([
            [
                'hanzi' => '我',
                'pinyin' => 'wǒ',
                'meaning' => 'tôi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hanzi' => '喜欢',
                'pinyin' => 'xǐhuan',
                'meaning' => 'thích',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'hanzi' => '学生',
                'pinyin' => 'xuésheng',
                'meaning' => 'học sinh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        $grammarPointId = DB::table('grammar_points')->insertGetId([
            'title' => 'Cách dùng 喜欢',
            'structure' => 'Chủ ngữ + 喜欢 + tân ngữ',
            'explanation' => 'Dùng 喜欢 để diễn tả sở thích.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('grammar_examples')->insert([
            'grammar_point_id' => $grammarPointId,
            'hanzi' => '我喜欢茶。',
            'pinyin' => 'Wǒ xǐhuan chá.',
            'meaning' => 'Tôi thích trà.',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('search.index', ['q' => '我喜欢学生。']))
            ->assertOk()
            ->assertJsonCount(3, 'vocabularies')
            ->assertJsonCount(1, 'grammarPoints')
            ->assertJsonFragment(['hanzi' => '我'])
            ->assertJsonFragment(['hanzi' => '喜欢'])
            ->assertJsonFragment(['hanzi' => '学生']);

        $this->actingAs($user)
            ->get(route('search.index', ['q' => 'Tôi thích trà.']))
            ->assertOk()
            ->assertJsonCount(1, 'grammarPoints')
            ->assertJsonPath('grammarPoints.0.title', 'Cách dùng 喜欢');

        $this->actingAs($user)
            ->get(route('search.index', ['q' => 'Tôi muốn học sinh']))
            ->assertOk()
            ->assertJsonCount(2, 'vocabularies')
            ->assertJsonFragment(['hanzi' => '我'])
            ->assertJsonFragment(['hanzi' => '学生']);
    }

    public function test_search_results_can_open_the_matching_vocabulary_and_grammar_pages(): void
    {
        $user = $this->createUser();
        $vocabularyId = DB::table('vocabularies')->insertGetId([
            'hanzi' => '学生',
            'pinyin' => 'xuésheng',
            'meaning' => 'học sinh',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $grammarPointId = DB::table('grammar_points')->insertGetId([
            'title' => 'Cách dùng 的',
            'explanation' => 'Dùng 的 để bổ nghĩa danh từ.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('vocabulary.user', ['word_id' => $vocabularyId]))
            ->assertOk()
            ->assertSee('data-word="学生"', false)
            ->assertSee('học sinh');

        $grammarResponse = $this->actingAs($user)
            ->get(route('grammar.user', ['grammar_id' => $grammarPointId]))
            ->assertOk();
        $this->assertSame(
            1,
            preg_match('/<script\b[^>]*id="grammarData"[^>]*>(.*?)<\/script>/s', $grammarResponse->getContent(), $matches),
        );
        $grammarData = json_decode($matches[1], true, flags: JSON_THROW_ON_ERROR);
        $this->assertContains('Cách dùng 的', array_column($grammarData, 'title'));
    }

    public function test_user_can_update_profile_and_log_out(): void
    {
        Storage::fake('public');
        $user = $this->createUser();
        $oldAvatarPath = UploadedFile::fake()->image('old-avatar.png')->store('avatars', 'public');
        $user->update(['avatar_url' => $oldAvatarPath]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'full_name' => 'Nguyen Van A',
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => UploadedFile::fake()->image('avatar.png'),
                'hsk_target' => 'HSK2',
                'role' => 'ADMIN',
                'is_active' => false,
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'full_name' => 'Nguyen Van A',
            'hsk_target' => 'HSK2',
            'role' => 'USER',
            'is_active' => true,
        ]);
        $user->refresh();
        $this->assertStringStartsWith('avatars/', $user->getRawOriginal('avatar_url'));
        Storage::disk('public')->assertExists($user->getRawOriginal('avatar_url'));
        Storage::disk('public')->assertMissing($oldAvatarPath);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_new_user_uses_logo_as_the_default_avatar(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('images/logoblue.png')
            ->assertDontSee('avatar-default.svg');
    }

    public function test_registration_uses_schema_fields_and_ignores_privilege_fields(): void
    {
        $this->get('/register')
            ->assertOk()
            ->assertSee('name="full_name"', false)
            ->assertSee('name="username"', false)
            ->assertDontSee('name="avatar_url"', false)
            ->assertDontSee('name="avatar"', false)
            ->assertDontSee('name="name"', false);

        $this->post('/register', [
            'full_name' => 'Nguyen Van B',
            'username' => 'nguyen_van_b',
            'email' => 'nguyen-b@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'avatar_url' => 'https://example.com/avatar.png',
            'role' => 'ADMIN',
            'is_active' => false,
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'username' => 'nguyen_van_b',
            'full_name' => 'Nguyen Van B',
            'email' => 'nguyen-b@example.com',
            'avatar_url' => null,
            'role' => 'USER',
            'is_active' => true,
        ]);
    }

    private function createUser(): User
    {
        return User::create([
            'username' => 'test-user',
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
    }
}
