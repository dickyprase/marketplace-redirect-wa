<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self' https://wa.me https://api.whatsapp.com",
            "frame-ancestors 'self'",
            "img-src 'self' data: https:",
            "script-src 'self' 'unsafe-inline' https://cdn.ckeditor.com https://cdn.jsdelivr.net https://code.jquery.com https://cdn.datatables.net",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.ckeditor.com https://cdn.jsdelivr.net https://cdn.datatables.net",
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdn.datatables.net",
            "connect-src 'self'",
            "frame-src 'self' https://www.google.com https://maps.google.com https://www.google.co.id",
            "object-src 'none'",
        ]));

        return $response;
    }
}
