<?php
declare(strict_types=1);

namespace App\Modules\Auth\Application\DTOs\Auth;

final readonly class AuthResponseDTO
{
    public function __construct(
        public string $token,
        public array $user,  
    ) {}
}