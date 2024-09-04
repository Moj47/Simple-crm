<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
  use RefreshDatabase;
    public function testProjectIndexShowAllProjects(): void
    {
        $response=$this->get(route('projects.index'));
        $response->assertOk();
        $response->assertViewHas('projects');
    }
    public function testCreateWithStoreMethod()
    {
        $project=Project::factory()->make();
        $this->post(route('projects.store',$project),
        $project->toArray())->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects',$project->toArray());

    }
    public function testProjectEditWithUpdateMethod()
    {
        $project=Project::factory()->create();
        $task=Task::factory()->count(rand(1,6))->create(['user_id'=>$project->user_id,'client_id'=>$project->client_id,'project_id'=>$project->id]);
        $this->assertDatabaseHas('projects',$project->toArray());

        $project2=Project::factory()->make();

        $this->put(route('projects.update',$project),
        $project2->toArray())->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects',$project2->toArray());

    }
}
