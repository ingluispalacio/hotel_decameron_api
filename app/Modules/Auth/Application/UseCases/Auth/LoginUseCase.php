<?php
declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Auth;

use App\Modules\Auth\Application\DTOs\Auth\LoginDTO;
use App\Modules\Auth\Application\DTOs\Auth\AuthResponseDTO;
use App\Modules\Auth\Domain\Enums\UserStatusEnum;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Modules\Auth\Infrastructure\Models\User;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(LoginDTO $dto): AuthResponseDTO
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user || !Hash::check($dto->password, $user->getPassword())) {
            throw new DomainException('Credenciales incorrectas.');
        }

        if ($user->getStatus() !== UserStatusEnum::ACTIVE) {
            throw new DomainException('Usuario inactivo o suspendido.');
        }

        // Obtener el modelo Eloquent para generar el token JWT
        $eloquentUser =User::find($user->getId());
        $token = JWTAuth::fromUser($eloquentUser);

        return new AuthResponseDTO($token, $user->toArray());
    }
}