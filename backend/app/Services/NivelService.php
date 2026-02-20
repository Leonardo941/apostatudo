<?php

namespace App\Services;

use App\Models\Nivel;
use App\Repositories\Contracts\NivelRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class NivelService
{
    public function __construct(
        private NivelRepositoryInterface $nivelRepository
    ) {}

    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return $this->nivelRepository->all($perPage);
    }

    public function find(int $id): ?Nivel
    {
        return $this->nivelRepository->find($id);
    }

    public function create(array $data): Nivel
    {
        return $this->nivelRepository->create($data);
    }

    public function update(int $id, array $data): Nivel
    {
        return $this->nivelRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        $nivel = $this->nivelRepository->find($id);

        if (!$nivel) {
            return false;
        }

        if ($nivel->profissionais()->count() > 0) {
            throw new ConflictHttpException(
                'Não é possível excluir um nível que possui profissionais associados.'
            );
        }

        return $this->nivelRepository->delete($id);
    }
}
