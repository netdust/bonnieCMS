<?php

/*
 * This file is part of Slipp.
 *
 * @author Stefan Vandermeulen <stefan@netdust.be>
 * @copyright 2013 netdust
 * @version 0.1.0
 * @package Slipp
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace API;

/**
 * Extended Slim base
 */

class App extends \Slim\Slim
{

    /**
     * Constructor
     * @param  array $userSettings Associative array of application settings
     */
    public function __construct(array $userSettings = array())
    {
        parent::__construct( $userSettings );
    }

    /********************************************************************************
     * Routing
     *******************************************************************************/

    /**
     * build in route function for handling controller loading
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function buildCallable( $controller )
    {
        return function( ) use ( $controller ) {

            $args = func_get_args();

            if( is_callable( $controller ) )
            {
                call_user_func_array( $controller, $args );
            }
            else
            {
                $segments = explode('@', $controller);
                $controller = preg_replace('/^\\\\/', '', $segments[0]);
                $method = $segments[1];
                $obj = new $controller();
                call_user_func_array( array( $obj, $method ), $args );
            }
        };
    }


    /**
     * Add callable to the end of the arguments array and passes it to \Slim\Slim::map
     * @see    map()
     * @return \Slim\Route
     */
    protected function beforeMap()
    {
        $args = func_get_args();
        $that = $this;

        array_walk($args[0], function(&$callable, $key) use( $that ) {
            if( $key!=0 )
                $callable = $that->buildCallable( $callable );
        });

        return call_user_func_array( '\Slim\Slim::map', $args[0] );

    }

    /**
     * Add generic route without associated HTTP method
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function map()
    {
        $args = func_get_args();

        return $this->beforeMap( $args );
    }

    /**
     * Add GET route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function get()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'GET' );
    }

    /**
     * Add POST route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function post()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'POST' );
    }

    /**
     * Add PUT route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function put()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'PUT' );
    }

    /**
     * Add PATCH route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function patch()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'PATCH' );
    }

    /**
     * Add DELETE route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function delete()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'DELETE' );
    }

    /**
     * Add OPTIONS route
     * @see    mapRoute()
     * @return \Slim\Route
     */
    public function options()
    {
        $args = func_get_args();

        return $this->beforeMap( $args )->via( 'OPTIONS' );
    }

    /********************************************************************************
     * Hooks
     *******************************************************************************/

    /**
     * Assign hook
     * @param  string   $name       The hook name
     * @param  mixed    $callable   A callable object
     * @param  int      $priority   The hook priority; 0 = high, 10 = low
     */
    public function hook($name, $callable, $priority = 10)
    {
        //parent::hook($name, $callable, $priority );
        parent::hook($name, $this->buildCallable( $callable ), $priority );

    }

    /********************************************************************************
     * Middleware
     *******************************************************************************/

    /**
     * Push middleware
     *
     * This method adds new middleware to the application middleware stack right after the current one
     * This enables us to add it from inside an called middleware
     * The argument must be an instance that subclasses Slim_Middleware.
     *
     * @param \Slim\Middleware
     */
    public function push($first, \Slim\Middleware $newMiddleware)
    {
        $newMiddleware->setApplication( $this );
        $newMiddleware->setNextMiddleware( $first->getNextMiddleware() );
        $first->setNextMiddleware( $newMiddleware );
    }

    /********************************************************************************
     * Extras
     *******************************************************************************/

    public function isRoute($route)
    {
        return (bool) preg_match( '|^' . $route . '.*|', $this->request->getResourceUri() );
    }

    public function isAPI()
    {
        return (bool) preg_match('|/api/v.*$|', $this->request->getPath());
    }

    /// Custom 404 error
    protected function defaultNotFound()
    {

        $mediaType = $this->request->getMediaType();

        if ('application/json' === $mediaType || true === $this->isAPI()) {

            $this->response->headers->set(
                'Content-Type',
                'application/json'
            );

            echo json_encode(
                array(
                    'code' => 404,
                    'message' => 'Not found'
                ),
                JSON_PRETTY_PRINT
            );

        } else {

            parent::defaultNotFound();

        }
    }

    // JSON friendly errors
    // NOTE: debug must be false
    // or default error template will be printed

    protected function defaultError($e)    {


        $mediaType = $this->request->getMediaType();

        // Standard exception data
        $error = array(
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        );

        if( is_subclass_of($e, 'api\\Exception') && $this->getMode()=='production') {
            $error['message'] = 'There was an internal error';
            unset($error['file'], $error['line']);
        }

        // Custom error data (e.g. Validations)
        if (method_exists($e, 'getData')) {
            $errors = $e->getData();
        }

        if (!empty($errors)) {
            $error['errors'] = $errors;
        }

        $this->getLog()->error($e->getMessage());

        if ('application/json' === $mediaType || true === $this->isAPI() ) {

            $this->response->headers->set(
                'Content-Type',
                'application/json'
            );
            echo json_encode($error, JSON_PRETTY_PRINT);

        } else {

            parent::defaultError($e);

        }

    }


}
