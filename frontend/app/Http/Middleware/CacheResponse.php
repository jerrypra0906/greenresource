<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, int $ttl = 3600): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Don't cache admin routes
        if ($request->is('admin*')) {
            return $next($request);
        }

        // Generate cache key based on full URL plus any headers that change the
        // response body for the same URL (e.g. ProductController's AJAX partial
        // fetch uses X-Partial on the same /products/{category} URL as the full
        // page). Without this, a full-page load and a partial fetch for the same
        // URL would overwrite each other's cache entry and serve the wrong body.
        $varyKey = $request->header('X-Partial', '');
        $cacheKey = 'response.' . md5($request->fullUrl() . '|' . $varyKey);

        // Try to get cached response
        $cached = Cache::get($cacheKey);
        if ($cached !== null && !empty($cached)) {
            return response($cached)->header('X-Cache', 'HIT');
        }

        // Get response
        $response = $next($request);

        // Only cache successful responses (not 404s or errors)
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            // Don't cache empty responses
            if (!empty($content)) {
                Cache::put($cacheKey, $content, $ttl);
                $response->header('X-Cache', 'MISS');
            }
        } else {
            // Don't cache error responses, but mark them
            $response->header('X-Cache', 'SKIP');
        }

        return $response;
    }
}

