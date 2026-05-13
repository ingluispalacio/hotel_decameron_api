<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\User;

use App\Modules\Auth\Application\DTOs\User\CreateUserDTO;
use App\Modules\Auth\Application\Mappers\UserEntityMapper;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use DomainException;
use Illuminate\Support\Facades\Hash;

class CreateUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(CreateUserDTO $dto): void
    {
        if ($this->userRepository->findByEmail($dto->email)) {
            throw new DomainException('El email ya está en uso.');
        }

        if (!$this->roleRepository->findById($dto->roleId)) {
            throw new DomainException('Rol no válido.');
        }

        $user = UserEntityMapper::toEntityFromCreateDTO($dto);
        $user->setPassword(Hash::make($dto->password));

        $this->userRepository->save($user);
    }
}