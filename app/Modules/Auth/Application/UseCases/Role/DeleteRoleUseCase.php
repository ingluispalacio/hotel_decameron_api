<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Role;

use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use DomainException;

class DeleteRoleUseCase
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(string $id): void
    {
        $role = $this->roleRepository->findById($id);
        if (!$role) {
            throw new DomainException('Rol no encontrado.');
        }
        $this->roleRepository->delete($role);
    }
}