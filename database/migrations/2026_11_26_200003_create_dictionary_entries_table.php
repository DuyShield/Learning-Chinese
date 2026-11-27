<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Từ điển chung (nạp từ CC-CEDICT) cho tra từ, tra câu, tra đoạn văn.
        // Không có timestamps vì nạp hàng loạt.
        Schema::create('dictionary_entries', function (Blueprint $table) {
            $table->id();
            $table->string('simplified', 50)->collation('utf8mb4_bin');
            $table->string('traditional', 50)->collation('utf8mb4_bin');
            $table->string('pinyin', 100);        // có dấu thanh: nǐ hǎo
            $table->string('pinyin_plain', 100);  // không dấu, không cách: nihao
            $table->text('definition');           // nghĩa tiếng Anh

            $table->index('simplified');
            $table->index('traditional');
            $table->index('pinyin_plain');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionary_entries');
    }
};
