<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects=Project::factory()->count(10)->create();
        foreach($projects as $project)
        {
            $task=Task::factory()->count(rand(1,6))->create(['user_id'=>$project->user_id,'client_id'=>$project->client_id,'project_id'=>$project->id]);
        }
    }
}
