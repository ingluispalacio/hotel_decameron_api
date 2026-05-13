<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Repositories;

use App\Modules\Auth\Domain\Entities\User as DomainUser;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Models\User as EloquentUser;
use App\Modules\Auth\Infrastructure\Mappers\EloquentUserMapper;

class UserRepository implements UserRepositoryInterface
{
    public function save(DomainUser $user): void
    {
        $data = EloquentUserMapper::toEloquent($user);
        $eloquent = EloquentUser::find($user->getId()) ?? new EloquentUser();
        $eloquent->fill($data);
        $eloquent->save();
    }

    public function findById(string $id): ?DomainUser
    {
        $eloquent = EloquentUser::withTrashed()->find($id);
        return $eloquent ? EloquentUserMapper::toDomain($eloquent) : null;
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $eloquentUser = EloquentUser::withTrashed()->with('role')->where('email', $email)->first();
        return $eloquentUser ? EloquentUserMapper::toDomain($eloquentUser) : null;
    }

    /** @return DomainUser[] */
    public function findAll(): array
    {
        $eloquents = EloquentUser::withTrashed()->get();
        return array_map(
            fn($eloquent) => EloquentUserMapper::toDomain($eloquent),
            $eloquents->all()
        );
    }

    public function delete(DomainUser $user): void
    {
        $eloquent = EloquentUser::find($user->getId());
        if ($eloquent) {
            $eloquent->delete(); // Soft delete si el modelo usa SoftDeletes
        }
    }
}
