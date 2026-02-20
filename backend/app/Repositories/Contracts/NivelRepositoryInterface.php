<?php

namespace App\Repositories\Contracts;

use App\Models\Nivel;
use Illuminate\Pagination\LengthAwarePaginator;

interface NivelRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Nivel;

    public function create(array $data): Nivel;

    public function update(int $id, array $data): Nivel;

    public function delete(int $id): bool;
}
