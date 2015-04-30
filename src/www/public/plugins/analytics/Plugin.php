<?php

namespace plugins\analytics;

use Slim\Slim;
use \controller\PageController;


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

        $hook = function ( $app, $settings ) {
            $plugin = $this->app->container->get(__NAMESPACE__);

            return function () use ( $app, $plugin, $settings )
            {
                echo $plugin->google( $settings->ga, \helpers\Util::host() );
            };
        };


        if( isset($this->settings) && isset( $this->settings->ga ) ){
            $app->hook( 'script', $hook( $app, $this->settings ) );
        }
        $this->next->call();
    }

    public function google( $ga ) {
        ob_start(); ?>

<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '<?php echo $ga; ?>', 'auto');
    ga('send', 'pageview');
</script>

        <?php return ob_get_clean();
    }




}

return __NAMESPACE__;