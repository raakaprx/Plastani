<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            JournalSeeder::class,
            ArticleSeeder::class, // Optional
        ]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  All seeders completed successfully!  ');
        $this->command->info('========================================');
    }
}
