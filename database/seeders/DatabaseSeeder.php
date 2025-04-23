<?php

namespace Database\Seeders;

use App\Models\{Project, Task};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 projects
        $projects = Project::factory()->count(10)->create();

        // For each project, create 15-20 tasks
        $projects->each(function ($project) {
            Task::factory()
                ->count(rand(15, 20))
                ->for($project) // Associate with the current project
                ->create();
        });
    }
}
