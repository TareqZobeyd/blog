<?php

namespace App\Enums;

enum UserRole: string
{
    case USER = 'user';
    case SUPER_ADMIN = 'super_admin';

    /**
     * Get the display label for the role
     */
    public function label(): string
    {
        return match($this) {
            self::USER => 'کاربر عادی',
            self::SUPER_ADMIN => 'مدیر ارشد',
        };
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this === self::SUPER_ADMIN;
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this === self::USER;
    }
}
