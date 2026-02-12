<?php

namespace App\Infrastructure\Http;

use FastRoute\RouteCollector;

final class Router
{
    public static function register(RouteCollector $r): void
    {
        $r->get('/api/postcodes', [
            \App\Infrastructure\Http\Controllers\PostcodeController::class,
            'search'
        ]);
    }
}
