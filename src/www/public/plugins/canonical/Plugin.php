<?php

namespace data\plugins\canonical;

use Slim\Slim;
use \controller\PageController;


class Plugin extends \Slim\Middleware {

    /**
     * @param array $config
     */
    function __construct($config = null)
    {

    }

    public function call()
    {

        $app = Slim::getInstance();
        $app->plugins = array_merge($app->plugins, array(
            __NAMESPACE__ => $this
        ));

        $hook = function ( $app ) {

            $plugin = $app->plugins[__NAMESPACE__];

            return function () use ( $app, $plugin )
            {

                $env = $app->environment();
                $current = $app->request()->getPathInfo() != '/'
                    ? $app->request()->getURL().str_replace( $app->request()->getPathInfo(), $env['slim.localization.original_path'], $app->request()->getPath() )
                    : $app->request()->getURL().$app->request()->getPath().trim($env['slim.localization.original_path'], '/');

                $target = $app->request()->getPathInfo() != '/'
                    ? 'http://' . $app->request()->getHost() . str_replace( $app->request()->getPathInfo(), '/'.$app->config('i18n.locale'), $app->request()->getPath() ) . $app->request()->getPathInfo()
                    : 'http://' . $app->request()->getHost() . $app->request()->getPath() . $app->config('i18n.locale');

                $app->page->canonical = $target;
                if( $current != $target )
                echo '<link rel="canonical" href="'.$target.'" />';
            };

        };

        $app->hook( 'header', $hook( $app ) );

        $this->next->call();
    }

}

return __NAMESPACE__;