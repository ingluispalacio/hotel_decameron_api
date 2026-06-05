<?php

declare(strict_types=1);

namespace App\Shared\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(401, 'Unauthenticated.');
        }

        $status = null;

        if (method_exists($user, 'getStatus')) {
            $status = $user->getStatus();
        } elseif (property_exists($user, 'status')) {
            $status = $user->status;
        }

        if ($status instanceof \BackedEnum) {
            $status = $status->value;
        }

        if ($status === null || strtolower((string) $status) !== 'active') {
            abort(403, 'User account is not active.');
        }

        return $next($request);
    }
}
