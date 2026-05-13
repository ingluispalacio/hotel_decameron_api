<?php

declare(strict_types=1);

namespace App\Shared\Domain;

/**
 * @template T
 */
final class PaginatedResult
{
    /**
     * @param array<T> $items
     */
    private function __construct(
        private array $items,
        private int $total,
        private int $perPage,
        private int $currentPage
    ) {}

    /**
     * @param array<T> $items
     */
    public static function create(array $items, int $total, int $perPage, int $currentPage): self
    {
        return new self($items, $total, $perPage, $currentPage);
    }

    /**
     * @return array<T>
     */
    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return (int) ceil($this->total / $this->perPage);
    }
}