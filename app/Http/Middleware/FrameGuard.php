<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrameGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (isset($response->headers)) {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', '*');
            $response->headers->set('Access-Control-Allow-Headers', '*');
            $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' https://nichesows.nichesite.org http://nichesows.nichesite.org *;");
            $response->headers->remove('X-Frame-Options');
        }

        return $response;
    }
}
