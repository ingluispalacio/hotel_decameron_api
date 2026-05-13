<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Role;

use App\Modules\Auth\Application\DTOs\Role\CreateRoleDTO;
use App\Modules\Auth\Application\Mappers\RoleEntityMapper;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;

class CreateRoleUseCase
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(CreateRoleDTO $dto): void
    {
        $role = RoleEntityMapper::toEntityFromCreateDTO($dto);
        $this->roleRepository->save($role);
    }
}