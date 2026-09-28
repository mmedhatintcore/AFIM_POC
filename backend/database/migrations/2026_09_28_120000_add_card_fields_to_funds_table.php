<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->decimal('return_1m', 8, 2)->nullable()->after('yield_1y');
            $table->date('inception_date')->nullable()->after('return_1m');
            $table->string('currency', 8)->default('EGP')->after('nav_price');
        });
    }

    public function down(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->dropColumn(['return_1m', 'inception_date', 'currency']);
        });
    }
};
