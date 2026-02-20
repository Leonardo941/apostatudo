<?php

namespace App\Providers;

use App\Repositories\Contracts\NivelRepositoryInterface;
use App\Repositories\Contracts\ProfissionalRepositoryInterface;
use App\Repositories\Contracts\RefreshTokenRepositoryInterface;
use App\Repositories\NivelRepository;
use App\Repositories\ProfissionalRepository;
use App\Repositories\RefreshTokenRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NivelRepositoryInterface::class, NivelRepository::class);
        $this->app->bind(ProfissionalRepositoryInterface::class, ProfissionalRepository::class);
        $this->app->bind(RefreshTokenRepositoryInterface::class, RefreshTokenRepository::class);
    }
}
