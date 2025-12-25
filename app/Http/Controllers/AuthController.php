<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request, JwtService $jwt)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'Registered',
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'token' => $jwt->issueToken($user->id),
        ], 201);
    }

    public function login(Request $request, JwtService $jwt)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Logged in',
            'token' => $jwt->issueToken($user->id),
        ]);
    }

    public function refresh(Request $request, JwtService $jwt)
    {
        $user = $request->user();
        return response()->json([
            'message' => 'Refreshed',
            'token' => $jwt->issueToken($user->id),
        ]);
    }

    public function logout()
    {
        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json($user->only(['id', 'name', 'email', 'role']));
    }
}

