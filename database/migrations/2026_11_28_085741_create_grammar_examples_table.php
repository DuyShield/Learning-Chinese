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
        Schema::create('grammar_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grammar_point_id')->constrained()->cascadeOnDelete();
            $table->string('hanzi');
            $table->string('pinyin')->nullable();
            $table->string('meaning');
            $table->string('audio_url')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index(['grammar_point_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_examples');
    }
};
