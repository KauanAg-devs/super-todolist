<?php
namespace App\Services;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthService {
  public function __construct(private UserService $userService) {}

  function signup(array $userData) {

    $user = $this->userService->create([
      'email' => $userData['email'],
      'password' => $userData['password']
    ]);

    $user->sendEmailVerificationNotification();

    return Auth::attempt(['email' => $userData['email'], 'password' => $userData['password']]);
  }
}