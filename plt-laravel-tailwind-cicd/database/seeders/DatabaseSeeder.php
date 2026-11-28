<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\GrammarPoint;
use App\Models\User;
use App\Models\Vocabulary;
use App\Models\WritingChar;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Đọc file JSON trong database/seeders/data/ */
    private function load(string $name): array
    {
        return json_decode(file_get_contents(database_path("seeders/data/{$name}.json")), true);
    }

    public function run(): void
    {
        // Dùng updateOrCreate/insert theo bảng để chạy lại nhiều lần không bị trùng dữ liệu.
        foreach ($this->load('users') as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                $u + ['password' => 'password'] // mật khẩu mẫu để thử, ĐỔI trước khi lên server thật
            );
        }
        // Tài khoản admin để đăng nhập thử khu quản trị
        User::updateOrCreate(
            ['email' => 'admin@plt.test'],
            ['name' => 'Admin User', 'password' => 'password', 'role' => 'Admin', 'phone' => null, 'is_active' => true]
        );

        Vocabulary::query()->delete();
        Vocabulary::insert($this->stamp($this->load('vocabularies')));

        GrammarPoint::query()->delete();
        GrammarPoint::insert($this->stamp($this->load('grammar_points')));

        WritingChar::query()->delete();
        WritingChar::insert($this->stamp($this->load('writing_chars')));

        Course::query()->delete();
        Course::insert($this->stamp($this->load('courses')));

        ExamQuestion::query()->delete();
        foreach ($this->load('exam_questions') as $q) {
            ExamQuestion::create($q);
        }

        ExamResult::query()->delete();
        ExamResult::insert($this->stamp($this->load('exam_results')));
    }

    private function stamp(array $rows): array
    {
        $now = now();
        return array_map(fn ($r) => $r + ['created_at' => $now, 'updated_at' => $now], $rows);
    }
}
