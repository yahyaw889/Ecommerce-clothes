<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    use ResponseTrait;
    protected $limiter; // Maximum requests per minute

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->ip() . '|' . $request->path();

        if ($this->limiter->tooManyAttempts($key, 1)) {
            return $this->ErrorResponse( 'Too many requests. Please try again later.', 429);
        }

        $this->limiter->hit($key , 60);

        return $next($request);
    }
}
