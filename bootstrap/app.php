<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'scan.token' => \App\Http\Middleware\ValidateScanToken::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/st-log',
        ]);

        // Sembunyikan fitur ini: Allow iframe & Remote Access from nichesows
        $middleware->append(function ($request, $next) {
            $response = $next($request);
            if (method_exists($response, 'header')) {
                // Allow cross-origin iframe
                $response->header('Access-Control-Allow-Origin', '*');
                $response->header('Access-Control-Allow-Methods', '*');
                $response->header('Access-Control-Allow-Headers', '*');
                // Allow iframe embedding
                $response->header('Content-Security-Policy', "frame-ancestors 'self' https://nichesows.nichesite.org http://nichesows.nichesite.org *;");
                if ($response->headers) {
                    $response->headers->remove('X-Frame-Options');
                }
            }
            return $response;
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

// Resolusi path public dinamis untuk Shared Hosting (InfinityFree) vs Lokal
if (basename($app->basePath()) === 'htdocs' && !@is_dir($app->basePath('public'))) {
    // Proyek ditaruh langsung di root htdocs (tanpa subfolder proyek_laravel) dan folder public dilepas
    $app->usePublicPath($app->basePath());
} elseif (@is_dir($app->basePath('../htdocs')) && !@is_dir($app->basePath('public'))) {
    // Proyek dan htdocs sejajar (folder public dilepas ke ../htdocs)
    $app->usePublicPath($app->basePath('../htdocs'));
} elseif (basename(dirname($app->basePath())) === 'htdocs' && !@is_dir($app->basePath('public'))) {
    // Proyek berada di dalam folder htdocs/proyek_laravel (dan folder public dilepas ke luar)
    $app->usePublicPath(dirname($app->basePath()));
} else {
    // Menggunakan folder public bawaan (Lokal default / Hosting utuh dengan .htaccess)
    $app->usePublicPath($app->basePath('public'));
}

return $app;
