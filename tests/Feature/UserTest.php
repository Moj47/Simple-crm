<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testViewIndexOfUsers()
    {
        $user=User::factory()->create();
        $this->actingAs($user)
        ->get(route('users.index'))
        ->assertViewIs('users.index')
        ->assertViewHas('users');
    }
    public function testCreateWithStoreMethod()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $data=User::factory()->make(['phone_number'=>'09918264998']);

        $this->actingAs($user)
        ->post(route('users.store'),$data->toArray())
        ->assertRedirect();
        $this->assertDatabaseHas('users',[
            'phone_number'=>$data->phone_number,
            'name'=>$data->name,
            'email'=>$data->email
        ]);
    }
    public function testUpdateUserWithUpdateMethod()
    {
        $user1=User::factory()->create();
        $data=User::factory()->make(['phone_number'=>'09918264998']);
        $user=User::factory()->create(['type'=>'admin']);

        $response=$this->actingAs($user)
        ->put(route('users.update',$user1->id),$data->toArray())
        ->assertRedirect();
        $this->assertDatabaseHas('users',$data->toArray());

    }
    public function testWhereUserHasNotAnyPermission()
    {
        $user1=User::factory()->create();

        $user=User::factory()->create();
        $this->actingAs($user)->delete(route('users.destroy',$user1))->assertStatus(403);
        $this->actingAs($user)->get(route('users.edit',$user1))->assertStatus(403);
        $this->actingAs($user)->delete(route('users.force-delete',$user1))->assertStatus(403);
        $this->actingAs($user)->get(route('users.create'))->assertStatus(403);


    }
    public function testWhereUserIsNotAdminButCanEditItselfInformation()
    {
        $user1=User::factory()->create();
        $data=User::factory()->make(['phone_number'=>'09193896557']);
        $this->actingAs($user1)->get(route('users.edit',$user1))->assertViewIs('Users.edit');
        $this->actingAs($user1)->put(route('users.update',$user1),$data->toArray())->assertRedirect();
        $this->assertDatabaseHas('users',[
            'name'=>$data->name,
            'phone_number'=>$data->phone_number
        ]);
    }
    public function testRestoreDeletedUser()
    {
        $user=User::factory()->create(['type'=>'admin']);
        $deletedUser=User::factory()->create(['deleted_at'=>date(now())]);

        $this->assertSoftDeleted('users',$deletedUser->toArray());
        $this->actingAs($user)
        ->post(route('users.restore',$deletedUser))
        ->assertRedirect();
        $this->assertDatabaseHas('users',[
            'name'=>$deletedUser->name,
            'email'=>$deletedUser->email,
            'phone_number'=>$deletedUser->phone_number
        ]);
    }
}
