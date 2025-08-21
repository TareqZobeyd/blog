<?php

namespace Modules\Auth\Services;

use App\Models\User;

class LogoutService
{
    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout(User $user): array
    {
        // Revoke all tokens for the user
        $user->tokens()->delete();

        return [
            'message' => 'خروج موفقیت‌آمیز بود'
        ];
    }
}
