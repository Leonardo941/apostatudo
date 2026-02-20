<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $payload = auth('api')->payload();
        $userRole = $payload->get('role');

        if (!$userRole || !in_array($userRole, $roles)) {
            return response()->json(['message' => 'Acesso nao autorizado.'], 403);
        }

        return $next($request);
    }
}
