<?php

namespace App\Modules\Auth\Domain\Entities;

use App\Modules\Auth\Domain\Enums\UserStatusEnum;

class User
{
    private string $id;
    private string $firstName;
    private string $lastName;
    private \DateTimeImmutable $birthDate;
    private string $roleId;
    private string $email;
    private string $password;
    private string $address;
    private UserStatusEnum $status;
    private ?string $roleName;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private ?\DateTimeImmutable $deletedAt = null;
    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $birthDate,
        string $roleId,
        string $email,
        string $password,
        string $address,
        UserStatusEnum $status,
        ?string $roleName = null, 
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->roleId = $roleId;
        $this->email = $email;
        $this->password = $password;
        $this->address = $address;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->roleName = $roleName;
    }

    // Getters
    public function getId(): string
    {
        return $this->id;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getBirthDate(): \DateTimeImmutable
    {
        return $this->birthDate;
    }
    public function getRoleId(): string
    {
        return $this->roleId;
    }
    public function getRoleName(): ?string
    {
        return $this->roleName;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getAddress(): string
    {
        return $this->address;
    }
    public function getStatus(): UserStatusEnum
    {
        return $this->status;
    }
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Setters (solo los que tengan sentido en el dominio)
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
    public function setStatus(UserStatusEnum $status): void
    {
        $this->status = $status;
    }
    public function setPassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    // Método de dominio, por ejemplo:
    public function changeEmail(string $newEmail): void
    {
        // validaciones de email
        $this->email = $newEmail;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function setRoleId(string $roleId): void
    {
        $this->roleId = $roleId;
    }

    public function setEmail(string $email): void
    {
        // Aquí podrías reutilizar tu lógica de validación
        $this->email = $email;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function softDelete(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
            'address'    => $this->address,
            'role_title'  => $this->roleName,
            'status'     => $this->status->value,
            'created_at' => $this->createdAt?->format('Y-m-d H:i:s'),
        ];
    }
}
