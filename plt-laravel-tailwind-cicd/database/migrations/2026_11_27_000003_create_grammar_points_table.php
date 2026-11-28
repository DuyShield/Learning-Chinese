<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grammar_points', function (Blueprint $table) {
            $table->id();
            $table->string('zh', 50);
            $table->string('py', 100);
            $table->string('name');
            $table->unsignedTinyInteger('hsk')->default(1)->index();
            $table->text('description');
            $table->string('example')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grammar_points');
    }
};
