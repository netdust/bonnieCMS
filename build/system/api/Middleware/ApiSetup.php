<?php
namespace api\Middleware;

class ApiSetup extends \Slim\Middleware
{
    public function call()   {

        if ( $this->app->isAPI() ) {

            $this->app->group(
                '/api',
                function () {

                    // Get contacts
                    $this->app->get('/', function () {
                        // auto generate docs here
                    });

                    // Group for API Version 1
                    $this->app->group(
                        '/v1',
                        function () {
                            // Common to all sub routes
                            $controllerFactory = function ( \Slim\Route $route) {

                                $type = $route->getParams();
                                $type = array_shift($type);
                                $controller = 'API\\controller\\'. ucfirst($type).'Controller';

                                if (class_exists($controller)) {
                                    $this->app->controller = new $controller();
                                }
                                else {
                                    throw new Exception("Invalid data type given, ". $controller);
                                }
                            };

                            $this->app->get('/:model(/:id(/:function)?)?', $controllerFactory,
                                function($model, $id = false, $function = false) {
                                    $this->app->controller->get( $id, $function );
                                });

                            $this->app->post('/:model(/:id)?', $controllerFactory,
                                function($model, $id = false) {
                                    $this->app->controller->post( $id );
                                });

                            $this->app->map('/:model/:id', $controllerFactory,
                                function($model, $id = false) {
                                    call_user_func_array(array($this->app->controller, strtolower( $this->app->request->getMethod() ) ), array($id) );
                                })->via('PATCH', 'POST', 'PUT', 'DELETE');
                        }
                    );
                }
            );

        }
        $this->next->call();
    }
}
