<?php
declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Auth;

use App\Modules\Auth\Application\DTOs\Auth\RegisterUserDTO;
use App\Modules\Auth\Application\DTOs\Auth\LoginDTO;
use App\Modules\Auth\Application\DTOs\Auth\AuthResponseDTO;
use App\Modules\Auth\Application\Mappers\UserEntityMapper;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly LoginUseCase $loginUseCase,
    ) {}

    public function execute(RegisterUserDTO $dto): AuthResponseDTO
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new DomainException('El email ya está registrado.');
        }

        $role = $this->roleRepository->findById($dto->roleId);
        if (!$role) {
            throw new DomainException('Rol no válido.');
        }

        $user = UserEntityMapper::toEntityFromRegisterDTO($dto);
        $user->setPassword(Hash::make($dto->password));
        $this->userRepository->save($user);

        return $this->loginUseCase->execute(new LoginDTO($dto->email, $dto->password));
    }
}