<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
  use RefreshDatabase;
    public function testProjectIndexShowAllProjects(): void
    {
        $user=User::factory()->create(['type'=>'admin']);
        $response=$this->actingAs($user)
        ->get(route('projects.index'));
        $response->assertOk();
        $response->assertViewHas('projects');
    }
    public function testCreateWithStoreMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $project=Project::factory()->make();
        $this->actingAs($user)
        ->post(route('projects.store',$project),
        $project->toArray())->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects',$project->toArray());

    }
    public function testProjectEditWithUpdateMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $project=Project::factory()->create();
        $task=Task::factory()->count(rand(1,6))->create(['user_id'=>$project->user_id,'client_id'=>$project->client_id,'project_id'=>$project->id]);
        $this->assertDatabaseHas('projects',$project->toArray());

        $project2=Project::factory()->make();

        $this->actingAs($user)->put(route('projects.update',$project),
        $project2->toArray())->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects',$project2->toArray());

    }
    public function testProjectSoftDeleteWithDestroyMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $project=Project::factory()->create();
        $task=Task::factory()->count(rand(1,6))->create(['user_id'=>$project->user_id,'client_id'=>$project->client_id,'project_id'=>$project->id]);

        $response=$this->actingAs($user)
        ->delete(route('projects.destroy',$project))
        ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas($project);

    }
    public function testProjectForceDeleteWithDestroyMethodWhileItHasTasks()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $project=Project::factory()->create();
        $task=Task::factory()->count(rand(1,6))->create(['user_id'=>$project->user_id,'client_id'=>$project->client_id,'project_id'=>$project->id]);

        $response=$this->actingAs($user)
        ->delete(route('projects.destroy',$project))
        ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas($project);

        //now lets for force delete

        $this->actingAs($user)
        ->delete(route('projects.force-delete',$project->id))
        ->assertRedirect()
        ->assertSessionHas('status','Project belongs to task. Cannot delete.');
        $this->assertDatabaseHas($project);
    }
    public function testProjectForceDeleteWithDestroyMethodWhileItHasNotAnyTask()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $project=Project::factory()->create();

        $response=$this->actingAs($user)
        ->delete(route('projects.destroy',$project))
        ->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas($project);

        //now lets for force delete

        $this->actingAs($user)
        ->delete(route('projects.force-delete',$project->id))
        ->assertRedirect();
        $this->assertDatabaseMissing($project);
    }
    public function testWhereUserHasNotAnyPermission()
    {
        $project=Project::factory()->create();

        $user=User::factory()->create();
        $this->actingAs($user)->delete(route('projects.destroy',$project))->assertStatus(403);
        $this->actingAs($user)->get(route('projects.edit',$project))->assertStatus(403);
        $this->actingAs($user)->delete(route('projects.force-delete',$project))->assertStatus(403);
        $this->actingAs($user)->get(route('projects.create'))->assertStatus(403);


    }
}
