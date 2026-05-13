<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Repositories;

use App\Modules\Auth\Domain\Entities\Role as DomainRole;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use App\Modules\Auth\Infrastructure\Models\Role as EloquentRole;
use App\Modules\Auth\Infrastructure\Mappers\EloquentRoleMapper;

class RoleRepository implements RoleRepositoryInterface
{
    public function save(DomainRole $role): void
    {
        $data = EloquentRoleMapper::toEloquent($role);
        $eloquent = EloquentRole::find($role->getId()) ?? new EloquentRole();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?DomainRole
    {
        $eloquent = EloquentRole::withTrashed()->find($id);
        return $eloquent ? EloquentRoleMapper::toDomain($eloquent) : null;
    }

    /** @return DomainRole[] */
    public function findAll(): array
    {
        $eloquents = EloquentRole::withTrashed()->get();
        return array_map(
            fn($eloquent) => EloquentRoleMapper::toDomain($eloquent),
            $eloquents->all()
        );
    }

    public function delete(DomainRole $role): void
    {
        $eloquent = EloquentRole::find($role->getId());
        if ($eloquent) {
            $eloquent->delete(); // Soft delete si el modelo usa SoftDeletes
        }
    }
}