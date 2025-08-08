<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AdminService;
use App\Models\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserDoesNotExistsException;
use App\Exceptions\InvalidEmailException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase; 

    protected AdminService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AdminService();
    }

    public function test_create_admin_success()
    {
        $data = ['email' => 'teste@example.com', 'password' => '123456'];

        $user = $this->service->create($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['email'], $user->email);
        $this->assertNotEquals($data['password'], $user->password);
    }

    public function test_create_admin_already_exists()
    {
        $this->expectException(UserAlreadyExistsException::class);

        $data = ['email' => 'teste@example.com', 'password' => '123456'];

        $this->service->create($data);
        $this->service->create($data); 
    }

    public function test_update_admin_password_and_email()
    {
        $data = ['email' => 'old@example.com', 'password' => '123456'];
        $user = $this->service->create($data);

        $updatedUser = $this->service->update([
            'email' => 'old@example.com',
            'password' => '654321',
            'new_email' => 'new@example.com',
        ]);

        $this->assertEquals('new@example.com', $updatedUser->email);
        $this->assertNotEquals('654321', $updatedUser->password);
    }

    public function test_update_admin_email_already_in_use()
    {
        $this->expectException(InvalidEmailException::class);

        $user1 = $this->service->create(['email' => 'user1@example.com', 'password' => '123456']);
        $user2 = $this->service->create(['email' => 'user2@example.com', 'password' => '123456']);

        $this->service->update([
            'email' => 'user1@example.com',
            'new_email' => 'user2@example.com',
        ]);
    }

    public function test_delete_admin_success()
    {
        $data = ['email' => 'user@example.com', 'password' => '123456'];
        $this->service->create($data);

        $result = $this->service->delete(['email' => 'user@example.com']);
        $this->assertTrue($result);
    }

    public function test_delete_admin_does_not_exist()
    {
        $this->expectException(UserDoesNotExistsException::class);
        $this->service->delete(['email' => 'notfound@example.com']);
    }


    public function test_find_all_normal_users()
    {
        $normal1 = User::factory()->create(['is_admin' => false]);
        $normal2 = User::factory()->create(['is_admin' => false]);

        $admin = User::factory()->create(['is_admin' => true]);

        $users = $this->service->findAllNormalUsers();

        $this->assertCount(2, $users);
        $this->assertTrue($users->contains($normal1));
        $this->assertTrue($users->contains($normal2));
        $this->assertFalse($users->contains($admin));
    }

    public function test_find_all_admin_users()
    {
        $admin1 = User::factory()->create(['is_admin' => true]);
        $admin2 = User::factory()->create(['is_admin' => true]);

        $normal = User::factory()->create(['is_admin' => false]);

        $admins = $this->service->findAllAdminUsers();

        $this->assertCount(2, $admins);
        $this->assertTrue($admins->contains($admin1));
        $this->assertTrue($admins->contains($admin2));
        $this->assertFalse($admins->contains($normal));
    }
}
