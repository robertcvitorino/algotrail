<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(1)
            ->create([
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ]);

        $this->call([
            ShieldSeeder::class
        ]);
    }
}
