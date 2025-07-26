<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogMemoryUsage
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $memoryUsage = memory_get_usage(true) / 1024 / 1024; // MB単位
        Log::info(sprintf(
            '[%s] %s - Memory: %.2f MB',
            $request->method(),
            $request->path(),
            $memoryUsage
        ));

        return $response;
    }
}
