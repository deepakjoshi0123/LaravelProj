<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Member::factory(5)->create();
        \App\Models\Project::factory(20)->create();
        \App\Models\Proj_Mem::factory(30)->create();
        \App\Models\Task::factory(10)->create();
        \App\Models\Task_Mem::factory(20)->create();
        \App\Models\Status::factory(20)->create();
        \App\Models\Comment::factory(100)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
