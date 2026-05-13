<?php

namespace Tests\Feature;

use App\Modules\Auth\Application\UseCases\User\ListUsersUseCase;
use App\Modules\Auth\Domain\Enums\UserStatusEnum;
use App\Modules\Auth\Domain\Entities\User;
use DateTimeImmutable;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserIndexResponseTest extends TestCase
{
    public function test_index_endpoint_returns_a_valid_user_json_array(): void
    {
        // Arrange
        $expectedUsers = [
            new User(
                '9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d',
                'Jane',
                'Doe',
                new DateTimeImmutable('1990-01-01'),
                '123e4567-e89b-12d3-a456-426614174000',
                null,
                'jane.doe@example.com',
                'secret-password',
                'Calle Falsa 123',
                UserStatusEnum::ACTIVE,
                new DateTimeImmutable('2025-01-01T00:00:00+00:00'),
                new DateTimeImmutable('2025-01-02T00:00:00+00:00'),
            ),
            new User(
                'c56a4180-65aa-42ec-a945-5fd21dec0538',
                'Carlos',
                'Pérez',
                new DateTimeImmutable('1987-05-10'),
                '987e6543-e21b-32d3-a456-426614174999',
                null,
                'carlos.perez@example.com',
                'another-secret',
                'Avenida Siempre Viva 742',
                UserStatusEnum::INACTIVE,
                new DateTimeImmutable('2025-03-01T12:30:00+00:00'),
                new DateTimeImmutable('2025-03-05T15:45:00+00:00'),
            ),
        ];

        $this->app->bind(ListUsersUseCase::class, fn () => new class($expectedUsers) {
            public function __construct(private array $users)
            {
            }

            public function execute(): array
            {
                return $this->users;
            }
        });

        $this->withoutMiddleware();

        // Act
        $response = $this->get(sprintf('/%s/users', config('api.prefix')));

        // Assert
        $response->assertStatus(200)
            ->assertJsonCount(count($expectedUsers))
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'first_name',
                    'last_name',
                    'email',
                    'address',
                    'role_id',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->each(fn (AssertableJson $json) =>
                    $json->whereType('id', 'string')
                        ->whereType('first_name', 'string')
                        ->whereType('last_name', 'string')
                        ->whereType('email', 'string')
                        ->whereType('address', 'string')
                        ->whereType('role_id', 'string')
                        ->whereType('status', 'string')
                        ->whereType('created_at', 'string')
                        ->whereType('updated_at', 'string')
                )
            );

        $payload = $response->json();

        self::assertIsArray($payload);
        self::assertCount(count($expectedUsers), $payload);

        foreach ($payload as $index => $userData) {
            self::assertArrayHasKey('id', $userData);
            self::assertArrayHasKey('first_name', $userData);
            self::assertArrayHasKey('last_name', $userData);
            self::assertArrayHasKey('email', $userData);
            self::assertArrayHasKey('address', $userData);
            self::assertArrayHasKey('role_id', $userData);
            self::assertArrayHasKey('status', $userData);
            self::assertArrayHasKey('created_at', $userData);
            self::assertArrayHasKey('updated_at', $userData);

            $expectedUser = $expectedUsers[$index];

            self::assertSame($expectedUser->getId(), $userData['id']);
            self::assertSame($expectedUser->getFirstName(), $userData['first_name']);
            self::assertSame($expectedUser->getLastName(), $userData['last_name']);
            self::assertSame($expectedUser->getEmail(), $userData['email']);
            self::assertSame($expectedUser->getAddress(), $userData['address']);
            self::assertSame($expectedUser->getRoleId(), $userData['role_id']);
            self::assertSame($expectedUser->getStatus()->value, $userData['status']);

            $createdAt = new DateTimeImmutable($userData['created_at']);
            $updatedAt = new DateTimeImmutable($userData['updated_at']);

            self::assertSame($expectedUser->getCreatedAt()?->format('c'), $createdAt->format('c'));
            self::assertSame($expectedUser->getUpdatedAt()?->format('c'), $updatedAt->format('c'));
            self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?[+-]\d{2}:\d{2}$/', $userData['created_at']);
            self::assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?[+-]\d{2}:\d{2}$/', $userData['updated_at']);
        }
    }
}
