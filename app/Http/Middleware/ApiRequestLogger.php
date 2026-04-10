<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiRequestLogger
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            Log::channel('api')->error('API exception', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'payload' => $this->sanitizePayload($request),
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            throw $exception;
        }

        Log::channel('api')->info('API request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'status' => $response->getStatusCode(),
            'duration_ms' => round((microtime(true) - $startTime) * 1000, 2),
            'payload' => $this->sanitizePayload($request),
        ]);

        return $response;
    }

    protected function sanitizePayload(Request $request): array
    {
        return $request->except([
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'x-api-key',
        ]);
    }
}
