<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginService
{
    /**
     * Login user
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        // Find user by email
        $user = User::where('email', $data['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['اطلاعات وارد شده صحیح نیست.'],
            ]);
        }

        // Generate JWT token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
            'token' => $token,
            'message' => 'Login successful'
        ];
    }
}
