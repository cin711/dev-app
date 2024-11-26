<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function register(RegisterRequest $request)
    {
        $user = new User([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $this->userService->store($user);

        return response()->json([
            'token' => $user->createToken('auth')->plainTextToken,
        ]);
    }

    public function login(LoginRequest $request) {
        $user = $this->userService->getByEmail($request->get('email'));
        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            throw ValidationException::withMessages(['Invalid credentials!']);
        }

        return response()->json([
            'token' => $user->createToken('auth')->plainTextToken,
        ]);

    }
}