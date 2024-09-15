<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    public function testClienttIndexShowAllProjects(): void
    {
        $user=User::factory()->create();
        $response=$this->actingAs($user)->get(route('clients.index'));
        $response->assertOk();
        $response->assertViewHas('clients');
    }
    public function testCreateWithStoreMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);

        $client=Client::factory()->make(['phone'=>'989196543212']);
        $this->actingAs($user)->post(route('clients.store',$client),
        $client->toArray())->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients',$client->toArray());

    }
    public function testClientEditWithUpdateMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);

        $client=Client::factory()->create(['phone'=>'989196543212']);
        $this->assertDatabaseHas('clients',$client->toArray());

        $client2=Client::factory()->make(['phone'=>'09193896557']);

        $this->actingAs($user)->put(route('clients.update',$client),
        $client2->toArray())->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients',$client2->toArray());

    }
    public function testdestroy_method_deletes_client_and_redirects_back()
    {
        $client = Client::factory()->create();

        $this->actingAs(User::factory()->create(['type'=>'admin']));

        $response = $this->delete(route('clients.destroy', $client));

        $this->assertSoftDeleted('clients',$client->toArray());

        $response->assertRedirect();
    }
    public function testForcedeleteMethodAuthorizesUserAndForceDeletesClient()
    {
        $client = Client::factory()->create(['deleted_at' => now()]);

        $this->actingAs(User::factory()->create(['type'=>'admin']));

        $response = $this->delete(route('clients.force-delete', $client->id))
        ->assertRedirect();

        $this->assertDatabaseMissing('clients',$client->toArray());
        $this->assertNull(Client::onlyTrashed()->find($client->id));
    }

    // /**
    //  * @test
    //  */
    public function testForcedeleteMethodHandlesForeignKeyConstraintError()
    {
        // Create a soft-deleted client with a project
        $client = Client::factory()->create(['deleted_at' => now()]);
        $project = Project::factory()->create(['client_id' => $client->id]);

        // Login as a user with delete permission
        $this->actingAs(User::factory()->create(['type'=>'admin']));

        // Call the forcedelete method
        $response = $this->delete(route('clients.force-delete', $client));

        // Assert the response redirects back with an error message
        $response->assertRedirect();
        $response->assertSessionHas('status', 'Client belongs to project. Cannot delete.');
    }

    // /**
    //  * @test
    //  */
    public function testRestoreMethodAuthorizesUserAndRestoresClient()
    {
        $client = Client::factory()->create(['deleted_at' => now()]);
        $this->assertSoftDeleted('clients',$client->toArray());
        $this->actingAs(User::factory()->create(['type'=>'admin']));

        $response = $this->post(route('clients.restore', $client->id));

        $this->assertDatabaseHas('clients', [
            'name'=>$client->name,
            'email'=>$client->email,
            'phone'=>$client->phone,
        ]);

        $this->assertNotNull(Client::find($client->id));
    }
}

