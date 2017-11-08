<?php
namespace GraphiQLMiddleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class GraphiQLMiddleware
{
    /**
     * @var string
     */
    private $graphiqlRoute = '';

    /**
     * @var string
     */
    private $graphqlRoute = '';

    /**
     * GraphiQLMiddleware constructor.
     * @param string $graphiqlRoute
     * @param string $graphqlRoute
     */
    public function __construct($graphiqlRoute = '/graphiql', $graphqlRoute = '/graphql')
    {
        $this->graphiqlRoute = $graphiqlRoute;
        $this->graphqlRoute = $graphqlRoute;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable|null $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        if ($request->getMethod() === 'GET' && $this->graphiqlRoute === $request->getUri()->getPath()) {
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

        return substr_replace($requestURI, $this->graphqlRoute, $routePosition, strlen($requestPath));
    }
}
