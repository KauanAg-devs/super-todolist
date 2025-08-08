<?php
namespace App\Services;
use App\Http\Requests\SignupRequest;
use App\Services\UserService;

class AuthService {
  public function __construct(private UserService $userService) {}

  function signup(array $userData) {

    $this->userService->create([
      'email' => $userData['email'],
      'password' => $userData['password']
    ]);

    return Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']]);
  }
}