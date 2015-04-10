<?php

// Cache Middleware (inner)
//$app->add(new API\Middleware\Cache('/api/v1'));

// Manage Rate Limit
//$app->add(new API\Middleware\RateLimit('/api/v1'));

// JSON Middleware
//$app->add(new API\Middleware\JSON('/api/v1'));

// Parses JSON body
//$app->add(new \Slim\Middleware\ContentTypes());

$app->group(
    '/api',
    function () use ($app, $log) {

        // Get contacts
        $app->get('/', function () {
            // auto generate docs here
        });

        // Group for API Version 1
        $app->group(
            '/v1',
            function () use ($app, $log) {
                // Common to all sub routes
                $controllerFactory = function ( \Slim\Route $route) use ( $app ) {

                    $type = $route->getParams();
                    $type = array_shift($type);
                    $controller = 'API\\controller\\'. ucfirst($type).'Controller';

                    if (class_exists($controller)) {
                        $app->controller = new $controller();
                    }
                    else {
                        throw new Exception("Invalid data type given, ". $controller);
                    }
                };

                $app->get('/:model(/:id(/:function)?)?', $controllerFactory,
                    function($model, $id = false, $function = false) use ($app) {
                        $app->controller->get( $id, $function );
                    });

                $app->post('/:model(/:id)?', $controllerFactory,
                    function($model, $id = false) use ($app) {
                        $app->controller->post( $id );
                    });

                $app->map('/:model/:id', $controllerFactory,
                    function($model, $id = false) use ($app) {
                        call_user_func_array(array($app->controller, strtolower( $app->request->getMethod() ) ), array($id) );
                    })->via('PATCH', 'POST', 'PUT', 'DELETE');
            }
        );
    }
);
