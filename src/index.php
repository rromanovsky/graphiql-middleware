<?php
require_once "../vendor/autoload.php";

$router = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $routeCollector) {
    $routeCollector->addRoute('GET', '/', 'GraphiQLMiddleware\Controller');
    $routeCollector->addRoute('GET', '/graphiql', ['GraphiQLMiddleware\Controller\GraphiQLController', 'show']);
});

$route = $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        list( , $controller, $parameters) = $route;
        list($class, $method) = $controller;
        call_user_func_array(array(new $class, $method), $parameters);
        break;
}
