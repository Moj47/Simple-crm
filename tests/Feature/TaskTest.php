<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    public function testIndexMethodForTask()
    {
        $user = User::factory()->create(['type' => 'admin']);
        $this->actingAs($user)->get(route('tasks.index'))->assertOk()
            ->assertViewHasAll(Task::all()->toArray());
    }
    public function testGuestCanNotStoreTask()
    {
        $response = $this->post(route('tasks.store'), [
            'name' => 'Test Task',
            'description' => 'Test Description',
            'deadline' => '2023-01-01',
            'user_id' => 1,
            'client_id' => 1,
            'project_id' => 1,
            'status' => 1,
        ]);

        $response->assertRedirect(route('login'));
    }

    public function testAdminCanStoreTask()
    {
        $user = User::factory()->create(['type' => 'admin']);
        $client = Client::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($user)->post(route('tasks.store'), [
            'name' => 'Test Task',
            'description' => 'Test Description',
            'deadline' => '2023-01-01',
            'user_id' => $user->id,
            'client_id' => $client->id,
            'project_id' => $project->id,
            'status' => 1,
        ]);

        $task = Task::first();

        $response->assertRedirect(route('tasks.index'));

        $this->assertEquals('Test Task', $task->name);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('2023-01-01', $task->deadline);
        $this->assertEquals($user->id, $task->user_id);
        $this->assertEquals($client->id, $task->client_id);
        $this->assertEquals($project->id, $task->project_id);
        $this->assertEquals(1, $task->status);
    }
    public function test_destroys_task_when_user_has_delete_permission()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $user = User::factory()->create(['type' => 'admin']);
        $task = Task::factory()->create(['user_id' => $user->id, 'client_id' => $client->id, 'project_id' => $project->id]);
        $this->actingAs($user);


        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function test_does_not_destroy_task_when_user_does_not_have_delete_permission()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user2->id, 'client_id' => $client->id, 'project_id' => $project->id]);
        $this->actingAs($user);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }
    public function test_restore_task_authorized_user()
{
    $project = Project::factory()->create();
    $client = Client::factory()->create();
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    $task = Task::factory()->create(['deleted_at'=>now(),'user_id' => $user2->id, 'client_id' => $client->id, 'project_id' => $project->id]);

    $response = $this->actingAs($user)->post(route('tasks.restore', $task));

    $this->assertSoftDeleted('tasks',$task->toArray());
    $response->assertStatus(403);
}

public function test_restore_task_unauthorized_user()
{
    $project = Project::factory()->create();
    $client = Client::factory()->create();
    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $task = Task::factory()->create(['deleted_at'=>now(),'user_id' => $user2->id, 'client_id' => $client->id, 'project_id' => $project->id]);


    $response = $this->actingAs($user2)->post(route('tasks.restore', $task));

    $this->assertSoftDeleted('tasks',$task->toArray());
}
}
