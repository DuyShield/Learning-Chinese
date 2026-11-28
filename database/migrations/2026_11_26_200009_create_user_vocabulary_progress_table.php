<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mỗi (người, từ) một dòng: vừa là tiến độ, vừa là lịch ôn lặp lại ngắt quãng
        Schema::create('user_vocabulary_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vocabulary_id')->constrained('vocabularies')->cascadeOnDelete();
            $table->tinyInteger('mastery_level')->default(0)->comment('Thang 0 đến 5');
            $table->integer('repetitions')->default(0);
            $table->integer('interval_days')->default(0);
            $table->decimal('ease_factor', 4, 2)->default(2.5);
            $table->integer('review_count')->default(0);
            $table->integer('correct_count')->default(0);
            $table->timestamp('next_review_at')->useCurrent();
            $table->timestamp('last_reviewed_at')->nullable();

            $table->unique(['user_id', 'vocabulary_id'], 'u_user_vocabulary');
            $table->index(['user_id', 'next_review_at'], 'idx_due_reviews');
        });

        // Cần MySQL 8.0.16 trở lên mới thực thi CHECK
        DB::statement(
            'ALTER TABLE user_vocabulary_progress
             ADD CONSTRAINT chk_mastery_level CHECK (mastery_level BETWEEN 0 AND 5)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('user_vocabulary_progress');
    }
};
