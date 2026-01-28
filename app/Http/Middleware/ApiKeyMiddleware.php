<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ----------------------------
        // 1. Validate API Key
        // ----------------------------
        $clientKey = $request->header('X-API-KEY');
        $serverKey = env('API_SECRET_KEY');

        if (!$clientKey || $clientKey !== $serverKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized: Invalid API Key',
            ], 401);
        }

         // ----------------------------
        // 2. Domain Whitelisting (Recommended)
        // ----------------------------
        // $allowedDomains = [
        //     'https://your-react-domain.com',
        //     'https://www.your-react-domain.com',
        //     'http://localhost:3000',         // React local dev
        // ];

        // $origin = $request->headers->get('origin');

        // if ($origin && !in_array($origin, $allowedDomains)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Forbidden: Domain not allowed',
        //     ], 403);
        // }
        return $next($request);
    }
}
