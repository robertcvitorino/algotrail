<?php

namespace Database\Seeders;

use App\Models\Trail;
use Illuminate\Database\Seeder;

class TrailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Trail::factory()
            ->count(5)
            ->create();
    }
}
