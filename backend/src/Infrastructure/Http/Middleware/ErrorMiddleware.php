<?php

namespace App\Infrastructure\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

final class ErrorMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $exception) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Internal Server Error',
                /* Remove in production */
                'message' => $exception->getMessage()
            ]);
        }
    }
}
