<?php

namespace Database\Seeders;

use App\Models\Grh;
use App\Models\Service;
use App\Models\User;
use Database\Factories\ServiceFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Service::factory(1)->create();
        User::factory(1)->create();
        Grh::factory(1)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
