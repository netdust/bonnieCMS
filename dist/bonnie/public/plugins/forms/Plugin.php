<?php

namespace plugins\forms;
use Slim\Slim;

class Plugin extends \Slim\Middleware {

    protected $settings;

    function __construct( $config ) {
        $this->settings = $config;
    }

    public function call()
    {


        $app = Slim::getInstance();
        $this->app->container->singleton(__NAMESPACE__, function () {
            return $this;
        });// make them available for other classes */

        $hook = function ( $app ) {
            $plugin = $this->app->container->get(__NAMESPACE__);
            return function () use ( $app, $plugin )
            {
                // add css
                // add js
            };
        };

        //$app->hook('header', $hook( $app ) );

        $app->hook('admin.before.render', function ( $args ) use ($app) {
            //$this->render( $args );

        });

        $this->next->call();
    }

    /**
     * load js plugin for admin
     */
    protected function render( $args ){

        $args->modules[] = (array) array( "name"=>"Players", "path"=>"player", 'require'=>'../../../public/themes/default/players/module.js');
        array_walk($args->modules, function (&$item, $key)
        {
            $item['id']=$key;
        });

    }

}

return __NAMESPACE__;