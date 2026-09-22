<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finder_questions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('question');
            $table->json('options');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finder_questions');
    }
};
