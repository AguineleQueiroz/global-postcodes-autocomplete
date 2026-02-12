<?php

namespace App\Infrastructure\Http;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

final class Kernel
{
    public function handle($container, $request)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) {
            Router::register($r);
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo "Not Found";
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo "Method Not Allowed";
                break;

            case Dispatcher::FOUND:
                [$class, $method] = $routeInfo[1];

                $controller = $container->get($class);
                return $controller->$method();
        }
    }
}

