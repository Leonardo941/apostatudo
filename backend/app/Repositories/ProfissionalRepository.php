<?php

namespace App\Repositories;

use App\Models\Profissional;
use App\Repositories\Contracts\ProfissionalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfissionalRepository implements ProfissionalRepositoryInterface
{
    public function all(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Profissional::with('nivel');

        if ($search) {
            $query->where('nome', 'like', "%{$search}%");
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Profissional
    {
        return Profissional::with('nivel')->find($id);
    }

    public function create(array $data): Profissional
    {
        $profissional = Profissional::create($data);
        return $profissional->load('nivel');
    }

    public function update(int $id, array $data): Profissional
    {
        $profissional = Profissional::findOrFail($id);
        $profissional->update($data);
        return $profissional->load('nivel');
    }

    public function delete(int $id): bool
    {
        return Profissional::findOrFail($id)->delete();
    }
}
