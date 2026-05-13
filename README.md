# Decameron API

API REST desarrollada con Laravel 12 y PHP 8.2 para gestionar usuarios, roles, hoteles, ciudades, tipos de habitación y alojamientos.

## Descripción

Esta API implementa un backend modular con una arquitectura basada en `app/Modules`. Incluye:

- Autenticación JWT para rutas protegidas.
- Controladores y casos de uso separados por dominio (`Auth`, `Hotel`).
- Respuestas JSON consistentes y fáciles de consumir.
- Pruebas automatizadas con PHPUnit.
- Integración de CI con GitHub Actions.

## Tecnologías y herramientas usadas

- `Laravel 12` como framework principal.
- `PHP 8.2`.
- `Tymon\JWTAuth` para autenticación JWT.
- `PHPUnit 11` para pruebas unitarias y funcionales.
- `Composer` para dependencias PHP.
- `GitHub Actions` para CI.
- `SQLite` en memoria durante pruebas.
- `Scribe` para documentación de API (opcional).

## Instalación local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Si usas Sail:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```

## Ejecutar la aplicación

```bash
php artisan serve
```

La API usa el prefijo de configuración `config('api.prefix')`, por defecto `v1`. Ejemplo de base de ruta:

```text
http://localhost:8000/v1
```

## Pruebas

Ejecuta todos los tests con:

```bash
composer test
```

El proyecto incluye una prueba funcional para validar respuestas JSON del endpoint de usuarios.

## CI / GitHub Actions

Se agregó un workflow de CI en `.github/workflows/ci.yml` que:

- ejecuta `composer install`
- genera la clave de aplicación
- prepara el entorno de testing
- ejecuta migraciones en SQLite en memoria
- corre `composer test`

Esto permite validar cada Pull Request automáticamente.

## Endpoints principales

### Autenticación pública

- `POST /v1/auth/login` — iniciar sesión.
- `POST /v1/auth/register` — registrar usuario.

### Rutas privadas (requieren JWT)

- `POST /v1/auth/logout` — cerrar sesión.
- `POST /v1/auth/me` — obtener datos del usuario autenticado.

### Gestión de usuarios

- `GET /v1/users` — listar usuarios.
- `POST /v1/users` — crear usuario.
- `GET /v1/users/{id}` — obtener usuario por ID.
- `PUT /v1/users/{id}` — actualizar usuario.
- `PATCH /v1/users/{id}` — actualizar usuario.
- `DELETE /v1/users/{id}` — eliminar usuario.

### Gestión de roles

- `GET /v1/roles`
- `POST /v1/roles`
- `GET /v1/roles/{id}`
- `PUT /v1/roles/{id}`
- `PATCH /v1/roles/{id}`
- `DELETE /v1/roles/{id}`

### Gestión de hoteles

- `GET /v1/hotels`
- `POST /v1/hotels`
- `PUT /v1/hotels/{id}`
- `PATCH /v1/hotels/{id}`
- `DELETE /v1/hotels/{id}`
- `GET /v1/hotels/{id}`

### Ciudades y configuraciones

- `GET /v1/cities`
- `POST /v1/cities`
- `POST /v1/hotel-configurations`
- `PUT /v1/hotel-configurations/{id}`
- `PATCH /v1/hotel-configurations/{id}`
- `DELETE /v1/hotel-configurations/{id}`

### Tipos de habitación y alojamientos

- `GET /v1/room-types`
- `GET /v1/room-types/{id}`
- `GET /v1/accommodations`
- `GET /v1/accommodations/{id}`
- `GET /v1/accommodations/name/{name}`

## Estructura principal del proyecto

- `app/Modules` — módulos del dominio (Auth, Hotel, etc.).
- `routes/api.php` — definición de rutas de la API.
- `tests/Feature` — pruebas de funcionalidad.
- `phpunit.xml` — configuración de PHPUnit.
- `.github/workflows/ci.yml` — pipeline de CI.

## Notas finales

- Las rutas privadas están protegidas por middleware `auth:api`.
- El endpoint de usuarios devuelve `status` como el valor del enum y fechas en formato ISO 8601.
- Revisa `config/api.php` si deseas cambiar el prefijo de versión de la API.
