<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Role;

use App\Modules\Auth\Application\DTOs\Role\UpdateRoleDTO;
use App\Modules\Auth\Domain\Enums\UserRoleEnum;
use App\Modules\Auth\Domain\Repositories\RoleRepositoryInterface;
use DomainException;

class UpdateRoleUseCase
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    public function execute(UpdateRoleDTO $dto): void
    {
        $role = $this->roleRepository->findById($dto->id);
        if (!$role) {
            throw new DomainException('Rol no encontrado.');
        }
         $titleString = $dto->title instanceof UserRoleEnum 
            ? $dto->title->value 
            : $dto->title;
        $role->setTitle($titleString);
        $role->setDescription($dto->description);
        $this->roleRepository->save($role);
    }
}