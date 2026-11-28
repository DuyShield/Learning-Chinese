<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('hsk')->index();
            $table->string('question');
            $table->json('options');            // mảng 4 đáp án
            $table->unsignedTinyInteger('answer'); // chỉ số đáp án đúng (0 đến 3)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
