<?php

namespace App\Infrastructure\Http;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Relay\Relay;
use Psr\Http\Message\ServerRequestInterface;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

final class Kernel
{
    public function handle($container, ServerRequestInterface $request): ResponseInterface
    {
        $dispatcher = simpleDispatcher(function(RouteCollector $route_collector_instance) {
            Router::register($route_collector_instance);
        });

        $routerMiddleware = function (ServerRequestInterface $request, callable $next) use ($dispatcher, $container) {

            $routeInfo = $dispatcher->dispatch(
                $request->getMethod(),
                $request->getUri()->getPath()
            );

            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    return new Response(404, [], 'Not Found');

                case Dispatcher::METHOD_NOT_ALLOWED:
                    return new Response(405, [], 'Method Not Allowed');

                case Dispatcher::FOUND:
                    [$class, $method] = $routeInfo[1];

                    $controller = $container->get($class);

                    return $controller->$method($request);

                default:
                    throw new \RuntimeException('Invalid routing state');
            }
        };

        $queue = [
            new Middleware\ErrorMiddleware(),
            new Middleware\LoggingMiddleware(),
            new Middleware\CorsMiddleware(),
            $routerMiddleware
        ];

        $relay = new Relay($queue);

        return $relay->handle($request);
    }
}