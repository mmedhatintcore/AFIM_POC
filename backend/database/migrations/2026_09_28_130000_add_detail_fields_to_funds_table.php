<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->date('price_date')->nullable()->after('currency');
            $table->decimal('return_1y', 10, 2)->nullable()->after('return_1m');
            $table->decimal('return_3y', 10, 2)->nullable()->after('return_1y');
            $table->decimal('return_5y', 10, 2)->nullable()->after('return_3y');
            $table->decimal('return_since_inception', 10, 2)->nullable()->after('return_5y');
            $table->json('asset_allocation')->nullable()->after('spark');
            $table->decimal('dividends_ytd', 10, 2)->nullable()->after('asset_allocation');
            $table->json('dividends')->nullable()->after('dividends_ytd');
        });
    }

    public function down(): void
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->dropColumn([
                'price_date', 'return_1y', 'return_3y', 'return_5y', 'return_since_inception',
                'asset_allocation', 'dividends_ytd', 'dividends',
            ]);
        });
    }
};
