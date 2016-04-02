# SlimFoundHandler

## Instalation
```
$ composer require z7zmey/slim-found-handler
```

## Application Configuration

```PHP
$app = new \Slim\App([
    'foundHandler' => function () {
        return new \z7zmey\SlimFoundHandler();
    }
]);
```

## Examples

You may use only required parameters
```PHP
$app->get('/', function (\Slim\Http\Response $response) {
    $response->getBody()->write("Hello");
    return $response;
});
```

You can get access the route argument by using the same variable name
```PHP
$app->get('/example1/{name}', function ($name) {
    echo "Hello, {$name}";
});
```

You can get access to all route arguments as array
```PHP
$app->get('/example2/{first}/{second}', function (array $routeArguments) {
    echo "{$routeArguments['first']} {$routeArguments['second']}";
});
```

The sequence of parameters doesn't matter
```PHP
use \Slim\Http\Response;
use \Slim\Http\Request;

$routeHandler = function ($first, Response $res, array $params, Request $req) {
    $second = $params['second'];
    $third = $req->getAttribute('route')->getArgument('third');

    $res->getBody()->write("{$first} {$second} {$third}");
    return $res;
};

$app->get('/example3/{first}/{second}/{third}', $routeHandler);
```

