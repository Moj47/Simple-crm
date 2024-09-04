<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testProjectIndexShowAllProjects(): void
    {
        $response=$this->get(route('projects.index'));
        $response->assertOk();
        $response->assertViewHas('projects');
    }
}
