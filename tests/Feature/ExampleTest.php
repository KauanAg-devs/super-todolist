<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{

   use RefreshDatabase;

    public function test_the_application_redirects_guest()
    {
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/auth/login');
    }

    public function test_authenticated_verified_user_can_access_home()
    {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('welcome');
    }
}
