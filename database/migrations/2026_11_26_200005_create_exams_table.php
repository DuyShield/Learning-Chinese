<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('hsk_level', ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])->nullable();
            $table->integer('duration_minutes');
            $table->decimal('pass_score', 5, 2)->default(60)
                ->comment('Phần trăm 0-100, so với score / total_points * 100');
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['hsk_level', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
