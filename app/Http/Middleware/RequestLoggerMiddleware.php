<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $response = $next($request);

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        if ($this->shouldSkip($request)) {
            return $response;
        }

        Log::channel('audit')->info('Incoming request', [
            'event'             => 'request',
            'method'            => $request->method(),
            'url'               => $request->fullUrl(),
            'execution_time_ms' => $executionTime,
            'status'            => $response->getStatusCode(),
            'ip'                => $request->ip(),
            'user_id'           => optional($request->user())->id,
        ]);

        return $response;
    }

    private function shouldSkip(Request $request): bool
    {
        return $request->is(
            'livewire/*',
            'build/*',
            'storage/*',
            'favicon.ico',
            '*.css',
            '*.js',
            '*.png',
            '*.jpg',
            '*.svg'
        );
    }
}