<?php

declare(strict_types=1);

namespace App\Shared\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Shared\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Log;

class Authorize
{
    public function handle(Request $request, Closure $next, string $firstRole, string ...$additionalRoles): Response
    {
        $roles = array_map(fn(string $role) => strtoupper(trim($role)), array_merge([$firstRole], $additionalRoles));

        Log::debug('[Authorize] Iniciando verificación', [
            'path' => $request->path(),
            'roles_requeridos' => $roles,
        ]);

        $user = $request->user();

        if ($user === null) {
            Log::debug('[Authorize] Usuario no autenticado (null)');
            abort(403, 'Access denied.');
        }

        Log::debug('[Authorize] Usuario encontrado', [
            'user_id' => $user->id ?? 'sin_id',
            'user_email' => $user->email ?? 'sin_email',
            'role_id' => $user->role_id ?? 'sin_role_id',
            'user_class' => get_class($user),
        ]);

        // --- Extracción del nombre del rol ---
        $roleName = null;
        $roleId = null;

        if (method_exists($user, 'getRoleName')) {
            $roleName = $user->getRoleName();
            Log::debug('[Authorize] Rol obtenido vía getRoleName()', ['roleName' => $roleName]);
        }

        if ($roleName === null && property_exists($user, 'roleName')) {
            $roleName = $user->roleName;
            Log::debug('[Authorize] Rol obtenido vía property $roleName', ['roleName' => $roleName]);
        }

        if ($roleName === null && isset($user->role)) {
            $roleValue = $user->role;
            if (is_string($roleValue)) {
                $roleName = $roleValue;
                Log::debug('[Authorize] Rol obtenido vía propiedad dinámica $role (string)', ['roleName' => $roleName]);
            } elseif (is_object($roleValue)) {
                $roleName = $roleValue->title ?? null;
                if ($roleName === null && method_exists($roleValue, 'getTitle')) {
                    $roleName = $roleValue->getTitle();
                }
                Log::debug('[Authorize] Rol obtenido vía propiedad dinámica $role objeto', [
                    'roleClass' => get_class($roleValue),
                    'roleName' => $roleName,
                ]);
            }
        }

        if ($roleName === null && isset($user->role_id)) {
            $roleId = $user->role_id;
            Log::debug('[Authorize] Se encontró role_id en el usuario pero no title', ['role_id' => $roleId]);
        }

        if ($roleName === null) {
            Log::debug('[Authorize] No se pudo extraer el rol del usuario', [
                'methods_exists' => method_exists($user, 'getRoleName'),
                'props' => array_keys(get_object_vars($user)),
                'role_id' => $roleId,
            ]);
        }

        if ($roleName === null) {
            Log::debug('[Authorize] roleName es null, acceso denegado');
            abort(403, 'Access denied.');
        }

        // --- Convertir a enum ---
        Log::debug('[Authorize] Intentando convertir roleName a enum', ['roleName_raw' => $roleName]);

        $userRole = UserRoleEnum::tryFrom(strtoupper(trim((string) $roleName)));

        if ($userRole === null) {
            Log::debug('[Authorize] Falló tryFrom: el valor no corresponde a ningún caso del enum', [
                'valor_transformado' => strtoupper(trim((string) $roleName))
            ]);
            abort(403, 'Access denied.');
        }

        Log::debug('[Authorize] Rol convertido exitosamente', ['userRole' => $userRole->value]);

        // --- Procesar roles permitidos ---
        $rolesArray = $roles;
        Log::debug('[Authorize] Roles permitidos (normalized)', ['roles' => $rolesArray]);

        $allowedRoles = array_filter(array_map(
            function(string $role): ?UserRoleEnum {
                $enum = UserRoleEnum::tryFrom(strtoupper(trim($role)));
                Log::debug('[Authorize] Mapeando rol permitido', [
                    'original' => $role,
                    'trimmed_upper' => strtoupper(trim($role)),
                    'enum_match' => $enum?->value ?? 'null'
                ]);
                return $enum;
            },
            $rolesArray
        ));

        Log::debug('[Authorize] Lista final de roles permitidos (enums)', [
            'allowed_enums' => array_map(fn($e) => $e->value, $allowedRoles)
        ]);

        // --- Comparación ---
        $hasAccess = in_array($userRole, $allowedRoles, true);

        Log::debug('[Authorize] Resultado de la comparación', [
            'userRole' => $userRole->value,
            'hasAccess' => $hasAccess,
            'is_strict_match' => $hasAccess
        ]);

        if (!$hasAccess) {
            Log::debug('[Authorize] Acceso denegado por rol no permitido');
            abort(403, 'Access denied.');
        }

        Log::debug('[Authorize] Acceso concedido, pasando al siguiente middleware');
        return $next($request);
    }
}