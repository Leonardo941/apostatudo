<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            ForceJsonResponse::class,
            AddQueuedCookiesToResponse::class,
        ]);
        $middleware->alias([
            'role' => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Dados invalidos.',
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'message' => 'Nao autenticado.',
                    ], 401);
                }

                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Recurso nao encontrado.',
                    ], 404);
                }

                if ($e instanceof HttpException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], $e->getStatusCode());
                }

                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return response()->json([
                    'message' => 'Erro interno do servidor.',
                ], $status);
            }
        });
    })->create();
