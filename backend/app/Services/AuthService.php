<?php

namespace App\Services;

use App\Repositories\Contracts\RefreshTokenRepositoryInterface;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository
    ) {}

    public function login(string $email, string $password): ?array
    {
        $credentials = ['email' => $email, 'password' => $password];

        if (!$token = auth('api')->attempt($credentials)) {
            return null;
        }

        return $this->respondWithTokens($token);
    }

    public function refresh(string $refreshToken): ?array
    {
        $storedToken = $this->refreshTokenRepository->findByToken($refreshToken);

        if (!$storedToken) {
            return null;
        }

        $this->refreshTokenRepository->deleteByToken($refreshToken);

        try {
            auth('api')->setUser($storedToken->user);
            $newAccessToken = auth('api')->login($storedToken->user);
        } catch (\Exception $e) {
            return null;
        }

        return $this->respondWithTokens($newAccessToken);
    }

    public function logout(): void
    {
        $user = auth('api')->user();

        if ($user) {
            $this->refreshTokenRepository->deleteByUserId($user->id);
        }

        auth('api')->logout();
    }

    private function respondWithTokens(string $accessToken): array
    {
        $user = auth('api')->user();
        $refreshToken = Str::random(64);

        $this->refreshTokenRepository->create(
            $user->id,
            $refreshToken,
            now()->addDays(7)
        );

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}
