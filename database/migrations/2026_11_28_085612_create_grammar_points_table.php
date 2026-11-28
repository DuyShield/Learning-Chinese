<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grammar_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 150);
            $table->string('structure')->nullable();
            $table->text('explanation');
            $table->text('note')->nullable();
            $table->enum('hsk_level', ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index(['hsk_level', 'order_index']);
            $table->unique(['lesson_id', 'title'], 'u_grammar_lesson_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_points');
    }
};
