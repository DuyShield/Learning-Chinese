<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writing_chars', function (Blueprint $table) {
            $table->id();
            $table->string('c', 4);
            $table->string('py', 30);
            $table->string('vi');
            $table->unsignedTinyInteger('strokes');
            $table->unsignedTinyInteger('hsk')->default(1);
            $table->string('info')->nullable();
            $table->string('example')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writing_chars');
    }
};
