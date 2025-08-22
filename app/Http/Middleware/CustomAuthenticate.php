<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class CustomAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // اگر API request باشد، redirect نکن
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // برای web requests، به login redirect کن
        return route('login');
    }
}
