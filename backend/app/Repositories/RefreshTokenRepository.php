<?php

namespace App\Repositories;

use App\Models\RefreshToken;
use App\Repositories\Contracts\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function create(int $userId, string $token, \DateTime $expiresAt): RefreshToken
    {
        return RefreshToken::create([
            'user_id' => $userId,
            'token' => hash('sha256', $token),
            'expires_at' => $expiresAt,
        ]);
    }

    public function findByToken(string $token): ?RefreshToken
    {
        return RefreshToken::where('token', hash('sha256', $token))
            ->where('expires_at', '>', now())
            ->first();
    }

    public function deleteByToken(string $token): bool
    {
        return RefreshToken::where('token', hash('sha256', $token))->delete() > 0;
    }

    public function deleteByUserId(int $userId): bool
    {
        return RefreshToken::where('user_id', $userId)->delete() > 0;
    }
}
