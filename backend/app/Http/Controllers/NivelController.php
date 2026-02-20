<?php

namespace App\Http\Controllers;

use App\Http\Requests\NivelRequest;
use App\Services\NivelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class NivelController extends Controller
{
    public function __construct(
        private NivelService $nivelService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $paginated = $this->nivelService->all($perPage);

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
            return response()->json(['message' => 'Erro ao listar níveis.'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $nivel = $this->nivelService->find($id);

            if (!$nivel) {
                return response()->json(['message' => 'Nível não encontrado.'], 404);
            }

            return response()->json($nivel);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar nível.'], 500);
        }
    }

    public function store(NivelRequest $request): JsonResponse
    {
        try {
            $nivel = $this->nivelService->create($request->validated());

            return response()->json($nivel, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar nível.'], 500);
        }
    }

    public function update(NivelRequest $request, int $id): JsonResponse
    {
        try {
            $nivel = $this->nivelService->update($id, $request->validated());

            return response()->json($nivel);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar nível.'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->nivelService->delete($id);

            return response()->json(null, 204);
        } catch (ConflictHttpException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir nível.'], 500);
        }
    }
}
