<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user=User::factory()->create();
        $client=Client::factory()->create();
        return [
            'title'=>$this->faker->title,
            'description'=>$this->faker->text,
            'user_id'=>$user->id,
            'client_id'=>$client->id,
            'deadline'=>$this->faker->date(),
            'status'=>Arr::random(['open','close'])
        ];
    }
}
