<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    public function testProjectIndexShowAllProjects(): void
    {
        $response=$this->get(route('clients.index'));
        $response->assertOk();
        $response->assertViewHas('clients');
    }
    public function testCreateWithStoreMethod()
    {
        $client=Client::factory()->make(['phone'=>'989196543212']);
        $this->post(route('clients.store',$client),
        $client->toArray())->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients',$client->toArray());

    }
    public function testProjectEditWithUpdateMethod()
    {
        $client=Client::factory()->create(['phone'=>'989196543212']);
        $this->assertDatabaseHas('clients',$client->toArray());

        $client2=Client::factory()->make(['phone'=>'09193896557']);

        $this->put(route('clients.update',$client),
        $client2->toArray())->assertRedirect(route('clients.index'));
        $this->assertDatabaseHas('clients',$client2->toArray());

    }
}
