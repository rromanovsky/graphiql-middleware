<?php
namespace GraphiQLMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GraphiQLMiddleware {
    public function __construct()
    {
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null) {
        if (!$next) {
            return $response;
        }

        return $next($request, $response);
    }
}
