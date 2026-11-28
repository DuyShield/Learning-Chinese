<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('zh', 50);            // chữ Hán
            $table->string('py', 100);           // pinyin có dấu
            $table->string('vi');                // nghĩa tiếng Việt
            $table->string('type', 30)->nullable(); // loại từ
            $table->unsignedTinyInteger('hsk')->default(1)->index();
            $table->string('topic', 60)->nullable()->index();
            $table->string('example')->nullable();
            $table->string('example_vi')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
