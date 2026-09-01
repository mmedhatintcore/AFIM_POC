<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->json('bio')->nullable()->after('role');
            $table->string('photo_path')->nullable()->after('bio');
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->json('mission')->nullable()->after('name');
            $table->json('members')->nullable()->after('mission');
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn(['mission', 'members']);
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['bio', 'photo_path']);
        });
    }
};
