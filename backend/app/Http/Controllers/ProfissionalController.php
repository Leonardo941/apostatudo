<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfissionalRequest;
use App\Services\ProfissionalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfissionalController extends Controller
{
    public function __construct(
        private ProfissionalService $profissionalService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $search = $request->input('search');
            $paginated = $this->profissionalService->all($perPage, $search);

            return response()->json([
                'data' => $paginated->items(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                    'last_page' => $paginated->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar profissionais.'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $profissional = $this->profissionalService->find($id);

            if (!$profissional) {
                return response()->json(['message' => 'Profissional não encontrado.'], 404);
            }

            return response()->json($profissional);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar profissional.'], 500);
        }
    }

    public function store(ProfissionalRequest $request): JsonResponse
    {
        try {
            $profissional = $this->profissionalService->create($request->validated());

            return response()->json($profissional, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar profissional.'], 500);
        }
    }

    public function update(ProfissionalRequest $request, int $id): JsonResponse
    {
        try {
            $profissional = $this->profissionalService->update($id, $request->validated());

            return response()->json($profissional);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar profissional.'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->profissionalService->delete($id);

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir profissional.'], 500);
        }
    }
}
