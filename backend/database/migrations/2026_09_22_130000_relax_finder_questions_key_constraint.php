<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `key` was a fixed "q1"/"q2"/"q3" slot identifier when the finder was a
     * hard-coded 3-question wizard. Now that questions are freely
     * add/edit/deletable from the admin, it's just an optional internal
     * label — drop the uniqueness requirement and allow it to be blank.
     * Raw SQL (no doctrine/dbal installed, so no Blueprint::change()).
     */
    public function up(): void
    {
        $hasUniqueIndex = collect(DB::select('SHOW INDEX FROM finder_questions'))
            ->contains('Key_name', 'finder_questions_key_unique');

        if ($hasUniqueIndex) {
            Schema::table('finder_questions', function ($table) {
                $table->dropUnique(['key']);
            });
        }

        DB::statement('ALTER TABLE finder_questions MODIFY `key` VARCHAR(255) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE finder_questions MODIFY `key` VARCHAR(255) NOT NULL');

        Schema::table('finder_questions', function ($table) {
            $table->unique('key');
        });
    }
};
