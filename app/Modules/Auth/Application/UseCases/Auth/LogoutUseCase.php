<?php
declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Auth;

use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutUseCase
{
    public function execute(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}