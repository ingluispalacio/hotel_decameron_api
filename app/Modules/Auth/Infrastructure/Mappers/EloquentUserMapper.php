<?php

declare(strict_types=1);

namespace App\Modules\Auth\Infrastructure\Mappers;

use App\Modules\Auth\Domain\Entities\User as DomainUser;
use App\Modules\Auth\Domain\Enums\UserStatusEnum;
use App\Modules\Auth\Infrastructure\Models\User as EloquentUser;

class EloquentUserMapper
{
    public static function toDomain(EloquentUser $eloquent): DomainUser
    {
        return new DomainUser(
            id: (string)$eloquent->id,
            firstName: $eloquent->first_name,
            lastName: $eloquent->last_name,
            birthDate: self::convertToImmutable($eloquent->birth_date),
            roleId: (string)$eloquent->role_id,
            email: $eloquent->email,
            password: $eloquent->password,
            address: $eloquent->address,
            status: $eloquent->status instanceof UserStatusEnum
                ? $eloquent->status
                : UserStatusEnum::from($eloquent->status),
            createdAt: $eloquent->created_at ? $eloquent->created_at->toDateTimeImmutable() : null,
            updatedAt: $eloquent->updated_at ? $eloquent->updated_at->toDateTimeImmutable() : null,
            // EXTRAER EL NOMBRE DEL ROL: 
            // Se asume que el modelo Eloquent tiene la relación 'role' y esta tiene un campo 'name' o 'title'
            roleName: $eloquent->role ? $eloquent->role->title : null 
        );
    }

    private static function convertToImmutable($date): \DateTimeImmutable
    {
        if ($date instanceof \Illuminate\Support\Carbon) {
            return $date->toDateTimeImmutable();
        }
        
        if (is_string($date)) {
            return new \DateTimeImmutable($date);
        }

        return new \DateTimeImmutable();
    }

    public static function toEloquent(DomainUser $user): array
    {
        return [
            'id'         => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'birth_date' => $user->getBirthDate()->format('Y-m-d'),
            'role_id'    => $user->getRoleId(),
            // role_name no se envía a la tabla users generalmente
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'address'    => $user->getAddress(),
            'status'     => $user->getStatus()->value,
            'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $user->getUpdatedAt()?->format('Y-m-d H:i:s'),
        ];
    }
}