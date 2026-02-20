<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login(
                $request->input('email'),
                $request->input('password')
            );

            if (!$result) {
                return response()->json(['message' => 'Credenciais inválidas.'], 401);
            }

            return $this->respondWithCookie($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao realizar login.'], 500);
        }
    }

    public function refresh(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->cookie('refresh_token');

            if (!$refreshToken) {
                return response()->json(['message' => 'Refresh token inválido ou expirado.'], 401);
            }

            $result = $this->authService->refresh($refreshToken);

            if (!$result) {
                return response()->json(['message' => 'Refresh token inválido ou expirado.'], 401);
            }

            return $this->respondWithCookie($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao renovar token.'], 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            $cookie = Cookie::create('refresh_token')
                ->withValue('')
                ->withExpires(time() - 3600)
                ->withPath('/api/auth')
                ->withHttpOnly(true)
                ->withSameSite('Lax');

            return response()->json(['message' => 'Logout realizado com sucesso.'])
                ->withCookie($cookie);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao realizar logout.'], 500);
        }
    }

    private function respondWithCookie(array $result): JsonResponse
    {
        $refreshToken = $result['refresh_token'];
        unset($result['refresh_token']);

        $cookie = Cookie::create('refresh_token')
            ->withValue($refreshToken)
            ->withExpires(time() + (7 * 24 * 60 * 60))
            ->withPath('/api/auth')
            ->withHttpOnly(true)
            ->withSameSite('Lax')
            ->withSecure(app()->environment('production'));

        return response()->json($result)->withCookie($cookie);
    }
}
