<?php

namespace App\Repositories\Contracts;

use App\Models\Profissional;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProfissionalRepositoryInterface
{
    public function all(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    public function find(int $id): ?Profissional;

    public function create(array $data): Profissional;

    public function update(int $id, array $data): Profissional;

    public function delete(int $id): bool;
}
