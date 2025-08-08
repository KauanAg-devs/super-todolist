<?php
namespace App\Services;

use App\Models\User;
use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\UserDoesNotExistsException;
use App\Exceptions\InvalidEmailException;
use Illuminate\Support\Facades\Hash;

class UserService {
  public function find(string $email): ?User {
    return User::where('email', $email)->first();
  }

  public function create(array $data): User {
    if ($this->find($data['email'])) {
      throw new UserAlreadyExistsException();
    }

    return User::create([
      'email' => $data['email'],
      'password' => $data['password'],
      'is_admin' => false,
    ]);
  }

  public function delete(array $data) {
    if (!($user = $this->find($data['email']))) {
      throw new UserDoesNotExistsException();
    }

    return $user->delete();
  }

 public function update(array $data): User {
    if (!($user = $this->find($data['email']))) {
      throw new UserDoesNotExistsException("User not found with email: {$data['email']}");
    }

    if (isset($data['password'])) {
      $user->password = $data['password']; 
    }

    #change email (always at the end of the method)
    if (!isset($data['new_email'])) {
      $user->save();
      return $user;
    }
    
    if ($this->find($data['new_email'])) {
      throw new InvalidEmailException("Another user already uses this email.");
    }

    $user->email = $data['new_email'];
    $user->save();

    return $user;
}

}
