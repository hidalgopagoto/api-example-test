<?php
use App\Http\Response;
use FastRoute\RouteCollector;

$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'App\Controller\HomeController@index');
    $r->addRoute('POST', '/reset', 'App\Controller\HomeController@reset');
    $r->addGroup('/balance', function(RouteCollector  $r) {
        $r->addRoute('GET', '[/]', 'App\Controller\BalanceController@show');
    });
    $r->addGroup('/event', function(RouteCollector  $r) {
        $r->addRoute('POST', '[/]', 'App\Controller\EventController@run');
    });
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        $response = new Response();
        $response->json(['success' => false, 'message' => 'Endpoint not found'], 404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        $response = new Response();
        $response->json(['success' => false, 'message' => 'Method not allowed'], 405);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode('@', $handler, 2);
        call_user_func_array(array(new $class, $method), $vars);
        break;
}