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
    function () use ($app) {

        // Get contacts
        $app->get('/', function () {
            // auto generate docs here
            echo 'ok';
        });

        // Group for API Version 1
        $app->group(
            '/v1',
            function () use ($app) {
                // Common to all sub routes
                $controllerFactory = function ( \Slim\Route $route) use ( $app ) {

                    $type = $route->getParams();
                    $type = array_shift($type);
                    $controller = 'api\\Controller\\'. ucfirst($type).'Controller';

                    if (class_exists($controller)) {
                        $app->controller = new $controller();
                    }
                    else {
                        throw new Exception("Invalid data type given, ". $controller);
                    }
                };

                // GET page/1/meta/8
                $app->get('/:model(/:id(/:function(/:fid)?)?)?', $controllerFactory,
                    function($model, $id = false, $function = false, $fid = false ) use ($app)
                    {
                        $param = $app->request()->get();
                        if( !$function ) $app->controller->get( $id, $model, $param );
                        else if(is_callable(array( $app->controller, $function ))) {
                            call_user_func_array( array( $app->controller, $function ), array($id, $fid, $param) );
                        }
                        else {
                            throw new Exception("Method does not exist, ". $app->controller);
                        }
                    });

                $app->post('/:model(/:id(/:function)?)?', $controllerFactory,
                    function($model, $id = false, $function = false) use ($app) {
                        $param = $app->request()->params();
                        if( !$function ) $app->controller->post( $id, $param );
                        else if(is_callable(array( $app->controller, $function ))) {
                            call_user_func_array( array( $app->controller, $function ), array($id, $param) );
                        }
                        else {
                            throw new Exception("Method does not exist, ". $app->controller);
                        }
                    });

                $app->patch('/:model/:id/:function', $controllerFactory,
                    function($model, $id = false, $function = false) use ($app) {
                        $param = (array) json_decode($app->request()->getBody());
                        if($function && is_callable(array( $app->controller, $function ))) {
                            call_user_func_array( array( $app->controller, $function ), array($id, $param) );
                        }
                        else {
                            throw new Exception("Method does not exist, ". $app->controller);
                        }
                    });

                /*
                $app->delete('/:model/:id(/:function(/:fid)?)?', $controllerFactory,
                    function($model, $id = false, $function = false, $fid = false ) use ($app) {
                        if( !$function ) $app->controller->delete( $id );
                        else if(is_callable(array( $app->controller, $function ))) {
                            call_user_func_array( array( $app->controller, $function ), array($id) );
                        }
                        else {
                            throw new Exception("Method does not exist, ". $app->controller);
                        }
                    });
                */

                $app->map('/:model/:id', $controllerFactory,
                    function($model, $id = false) use ($app) {
                        $param = (array) json_decode($app->request()->getBody());
                        call_user_func_array(array($app->controller, strtolower( $app->request->getMethod() ) ), array($id, $param) );
                    })->via('PATCH', 'PUT', 'DELETE');
            }
        );
    }
);
