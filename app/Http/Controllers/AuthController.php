<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\SignupRequest;

class AuthController extends Controller
{
      public function __construct(private AuthService $authService) {}

      public function signup(SignupRequest $signupRequest){
        if(!($success = $this->authService->signup($signupRequest->validated()))) {
            return redirect()->back()->withErrors([
                'email' => 'Falha na autenticação após cadastro.',
            ]);
        }
        
        return redirect()->intended('/');
      }
}
