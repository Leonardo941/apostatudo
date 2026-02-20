<?php

namespace App\Repositories\Contracts;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function create(int $userId, string $token, \DateTime $expiresAt): RefreshToken;

    public function findByToken(string $token): ?RefreshToken;

    public function deleteByToken(string $token): bool;

    public function deleteByUserId(int $userId): bool;
}
