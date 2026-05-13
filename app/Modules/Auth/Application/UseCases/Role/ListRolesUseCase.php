<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Role;

use App\Modules\Auth\Domain\Entities\Role;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;

class ListRolesUseCase
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * @return Role[]
     */
    public function execute(): array
    {
        return $this->roleRepository->findAll();
    }
}