<?php

namespace App\Repositories;

use App\Models\Nivel;
use App\Repositories\Contracts\NivelRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NivelRepository implements NivelRepositoryInterface
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return Nivel::withCount('profissionais')->paginate($perPage);
    }

    public function find(int $id): ?Nivel
    {
        return Nivel::find($id);
    }

    public function create(array $data): Nivel
    {
        return Nivel::create($data);
    }

    public function update(int $id, array $data): Nivel
    {
        $nivel = Nivel::findOrFail($id);
        $nivel->update($data);
        return $nivel;
    }

    public function delete(int $id): bool
    {
        return Nivel::findOrFail($id)->delete();
    }
}
