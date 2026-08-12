<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->boolean('is_enabled')->default(true);
            $table->json('title')->nullable();
            $table->json('subtitle')->nullable();
            $table->json('body')->nullable();
            $table->json('items')->nullable();
            $table->json('cta')->nullable();
            $table->json('extra')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('description');
            $table->json('body')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('fund_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('name');
            $table->json('description');
            $table->unsignedTinyInteger('risk_level')->default(0);
            $table->string('fund_group')->index();
            $table->string('illustration')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('name');
            $table->json('category_label')->nullable();
            $table->string('group_key')->index();
            $table->string('order_channel')->default('nbe');
            $table->json('platforms')->nullable();
            $table->unsignedTinyInteger('risk_level')->nullable();
            $table->decimal('nav_price', 12, 2)->nullable();
            $table->decimal('daily_change', 8, 2)->nullable();
            $table->decimal('yield_1y', 8, 2)->nullable();
            $table->json('spark')->nullable();
            $table->string('illustration')->nullable();
            $table->json('description')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('news_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('type')->index();
            $table->string('source');
            $table->json('title');
            $table->json('excerpt');
            $table->json('body');
            $table->timestamp('published_at')->nullable()->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->json('answer');
            $table->string('action_type')->nullable();
            $table->json('action_label')->nullable();
            $table->unsignedInteger('sort')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('timeline_milestones', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->json('title');
            $table->json('body');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('group')->index();
            $table->json('name');
            $table->json('role');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('responsibilities');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->nullable()->index();
            $table->json('phase');
            $table->json('question');
            $table->string('layout')->default('cards');
            $table->json('options');
            $table->unsignedInteger('sort')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->json('answers');
            $table->json('result');
            $table->string('locale', 5)->default('en');
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('locale', 5)->default('en');
            $table->boolean('is_read')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('survey_submissions');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('committees');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('timeline_milestones');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('news_posts');
        Schema::dropIfExists('funds');
        Schema::dropIfExists('fund_categories');
        Schema::dropIfExists('services');
        Schema::dropIfExists('sections');
    }
};
