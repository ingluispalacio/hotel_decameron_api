<?php
declare(strict_types=1);

namespace App\Modules\Auth\Application\UseCases\Auth;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use DomainException; // o use Exception, pero mejor DomainException
use Illuminate\Support\Facades\Auth;

class GetAuthenticatedUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Obtiene el usuario autenticado desde el token JWT.
     *
     * @throws DomainException Si el usuario no está autenticado o no existe en el repositorio.
     */
    public function execute(): User
    {
        // Obtener el ID del usuario desde el guard 'api' (configurado para JWT)
        $userId = Auth::guard('api')->id();

        if (!$userId) {
            throw new DomainException('No autenticado.');
        }

        // Buscar la entidad de dominio por ID
        $user = $this->userRepository->findById((string) $userId);

        if (!$user) {
            throw new DomainException('Usuario no encontrado.');
        }

        return $user;
    }
}