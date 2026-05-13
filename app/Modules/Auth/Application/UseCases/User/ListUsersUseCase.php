<?php

declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\User;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;

class ListUsersUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @return User[]
     */
    public function execute(): array
    {
        return $this->userRepository->findAll();
    }
}