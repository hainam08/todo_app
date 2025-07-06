<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Kiểm tra nếu route thuộc nhóm admin
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        // Mặc định cho user
        return route('login');
    }

    
}
