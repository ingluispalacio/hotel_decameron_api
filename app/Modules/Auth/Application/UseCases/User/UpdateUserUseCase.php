<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\User;

use App\Modules\Auth\Application\DTOs\User\UpdateUserDTO;
use App\Modules\Auth\Domain\Enums\UserStatusEnum;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use DomainException;

class UpdateUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(UpdateUserDTO $dto): void
    {
        $user = $this->userRepository->findById($dto->id);
        if (!$user) {
            throw new DomainException('Usuario no encontrado.');
        }

        if ($dto->firstName !== null) $user->setFirstName($dto->firstName);
        if ($dto->lastName !== null) $user->setLastName($dto->lastName);
        if ($dto->birthDate !== null) $user->setBirthDate(new \DateTimeImmutable($dto->birthDate));
        if ($dto->address !== null) $user->setAddress($dto->address);
        if ($dto->roleId !== null) $user->setRoleId($dto->roleId);
        if ($dto->status !== null) $user->setStatus(UserStatusEnum::from($dto->status));

        // Si se actualiza el email, verificar unicidad
        if ($dto->email !== null && $dto->email !== $user->getEmail()) {
            $existing = $this->userRepository->findByEmail($dto->email);
            if ($existing && $existing->getId() !== $user->getId()) {
                throw new DomainException('El email ya está en uso.');
            }
            $user->setEmail($dto->email);
        }

        $this->userRepository->save($user);
    }
}