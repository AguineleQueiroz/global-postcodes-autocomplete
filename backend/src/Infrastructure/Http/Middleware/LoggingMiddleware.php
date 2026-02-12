<?php

namespace App\Infrastructure\Http\Middleware;

use Psr\Http\Message\ServerRequestInterface;

final class LoggingMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        error_log(
            sprintf('[%s] %s %s', date('Y-m-d H:i:s'), $request->getMethod(), $request->getUri()->getPath())
        );
        return $next($request);
    }
}
