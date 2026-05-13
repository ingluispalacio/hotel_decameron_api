<?php

namespace App\Modules\Auth\Domain\Repositories;

use App\Modules\Auth\Domain\Entities\Role;

interface RoleRepositoryInterface
{
    public function save(Role $role): void;
    public function findById(string $id): ?Role;
    /** @return Role[] */
    public function findAll(): array;
    public function delete(Role $role): void;
}