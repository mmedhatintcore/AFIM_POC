<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            // 'auto' = the card shows today's date; 'manual' = it shows price_date.
            $table->string('price_date_mode', 10)->default('auto')->after('price_date');
        });
    }

    public function down(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->dropColumn('price_date_mode');
        });
    }
};
