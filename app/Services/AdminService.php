<?php
namespace App\Services;

use App\Models\User;
use App\Services\UserService;
use App\Exceptions\UserAlreadyExistsException;
use Illuminate\Support\Collection;
class AdminService extends UserService {
  public function create(array $data): User {
    if ($this->find($data['email'])) {
      throw new UserAlreadyExistsException();
    }

    return User::create([
      'email' => $data['email'],
      'password' => $data['password'],
      'is_admin' => true,
    ]);
  }

  public function findAllNormalUsers(): Collection {
    return User::where('is_admin', false)->get();
  }

  public function findAllAdminUsers(): Collection {
    return User::where('is_admin', true)->get();
  }
}
