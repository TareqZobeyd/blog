<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function register(array $data): array {
        \Log::info('RegisterService: Starting registration process', $data);
        
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            
            \Log::info('RegisterService: User created successfully', ['user_id' => $user->id]);
            
            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
                'message' => 'User registered successfully'
            ];
        } catch (\Exception $e) {
            \Log::error('RegisterService: Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
