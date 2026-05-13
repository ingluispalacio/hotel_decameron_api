<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\User;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use DomainException;

class GetUserByIdUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(string $id): User
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new DomainException('Usuario no encontrado.');
        }
        return $user;
    }
}