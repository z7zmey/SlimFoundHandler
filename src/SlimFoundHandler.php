<?php
namespace z7zmey;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

/**
 * Route callback strategy with route parameters as individual arguments.
 */
class SlimFoundHandler implements InvocationStrategyInterface
{

    /**
     * Invoke a route callable with request, response and all route parameters
     * as individual arguments.
     *
     * @param array|callable         $callable
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $routeArguments
     *
     * @return mixed
     */
    public function __invoke(
        callable $callable, 
        ServerRequestInterface $request, 
        ResponseInterface $response, 
        array $routeArguments
    ) 
    {
        if (is_array($callable) && $callable = (array)$callable) {
            $functionReflection = new \ReflectionMethod($callable[0], $callable[1]);
        } else {
            $functionReflection = new \ReflectionFunction($callable);
        }
        
        $parameters = $functionReflection->getParameters();
        
        $args = [];
        foreach ($parameters as $parameter) {
            $class = $parameter->getClass();
            
            if ($parameter->isArray()) {
                $args[] = $routeArguments;
                continue;
            } elseif ($class && $request instanceof $class->name) {
                $args[] = $request;
                continue;
            } elseif ($class && $response instanceof $class->name) {
                $args[] = $response;
                continue;
            } elseif (array_key_exists($parameterName = $parameter->getName(), $routeArguments)) {
                $args[] = $routeArguments[$parameterName];
                continue;
            }
            
            $args[] = null;
        }

        return call_user_func_array($callable, $args);
    }
}
