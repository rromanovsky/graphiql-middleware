<?php
namespace GraphiQLMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GraphiQLMiddleware
{
    /**
     * @var string
     */
    private $route = '';

    /**
     * @var string
     */
    private $graphqlURI = '/graphql';

    /**
     * @var bool
     */
    private $ingoreRoute = false;

    /**
     * @param array $params
     *
     * $params['route']       string
     * $params['ingoreRoute'] boolean
     * $params['graphqlURI']  string
     * ]
     */
    public function __construct(array $params = [])
    {
        if (array_key_exists('route', $params)) {
            $this->route = $params['route'];
        }

        if (array_key_exists('ingoreRoute', $params)) {
            $this->ingoreRoute = $params['ingoreRoute'];
        }

        if (array_key_exists('graphqlURI', $params)) {
            $this->graphqlURI = $params['graphqlURI'];
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($request->getMethod() === 'GET' && (
            $this->ingoreRoute || !empty($this->route) && $this->route === $request->getUri()->getPath()
        )) {
            $response->getBody()->write($this->render($request));

            return $response;
        }

        return $next ? $next($request, $response) : $response;
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private function render(ServerRequestInterface $request)
    {
        $graphqlPath = $this->getGraphqlPath($request);
        $template = new \Text_Template(__DIR__ . '/graphiql/index.html');
        $template->setVar(['graphqlPath' => $graphqlPath]);

        return $template->render();
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    private function getGraphqlPath(ServerRequestInterface $request)
    {
        $requestURI = $request->getServerParams()['REQUEST_URI'];
        $requestPath = $request->getUri()->getPath();
        $routePosition = strrpos($requestURI, $requestPath);

        return substr_replace($requestURI, $this->graphqlURI, $routePosition, strlen($requestPath));
    }
}
