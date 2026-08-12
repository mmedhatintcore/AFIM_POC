<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SectionSeeder::class,
            ServiceSeeder::class,
            FundSeeder::class,
            NewsSeeder::class,
            FaqSeeder::class,
            AboutSeeder::class,
            SurveySeeder::class,
        ]);
    }
}
