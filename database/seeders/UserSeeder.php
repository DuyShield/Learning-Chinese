<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Tài khoản mẫu để thử đăng nhập. Mật khẩu chung: password
 * Chỉ dùng khi phát triển, không đưa lên server thật.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            ['username' => 'admin', 'email' => 'admin@example.com', 'full_name' => 'Quản trị viên', 'hsk_target' => 'HSK6', 'role' => 'ADMIN'],
            ['username' => 'minhanh', 'email' => 'minhanh@example.com', 'full_name' => 'Nguyễn Minh Anh', 'hsk_target' => 'HSK3', 'role' => 'USER'],
            ['username' => 'quocbao', 'email' => 'quocbao@example.com', 'full_name' => 'Trần Quốc Bảo', 'hsk_target' => 'HSK2', 'role' => 'USER'],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                $u + [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
