<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

   public function test_signup_creates_user_and_logs_in()
{
    config(['session.driver' => 'array']);
    $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

    $postData = [
        'email' => 'teste@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->post('/auth/signup', $postData);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('users', [
        'email' => 'teste@example.com',
    ]);

    $this->assertAuthenticated();

    $this->assertEquals('teste@example.com', auth()->user()->email);
}

}