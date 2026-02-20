<?php

namespace App\Services;

use App\Models\Profissional;
use App\Repositories\Contracts\ProfissionalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfissionalService
{
    public function __construct(
        private ProfissionalRepositoryInterface $profissionalRepository
    ) {}

    public function all(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->profissionalRepository->all($perPage, $search);
    }

    public function find(int $id): ?Profissional
    {
        return $this->profissionalRepository->find($id);
    }

    public function create(array $data): Profissional
    {
        return $this->profissionalRepository->create($data);
    }

    public function update(int $id, array $data): Profissional
    {
        return $this->profissionalRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->profissionalRepository->delete($id);
    }
}
