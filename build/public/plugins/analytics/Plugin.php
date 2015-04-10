<?php

namespace plugins\analytics;

use Slim\Slim;
use api\controller\PageController;


class Plugin extends \Slim\Middleware {

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function __construct()
    {

    }

    public function call()
    {
        $app = Slim::getInstance();
        $app->plugins = array_merge($app->plugins, array(
            __NAMESPACE__ => $this
        ));

        $hook = function ( $app, $settings ) {
            $plugin = $app->plugins[__NAMESPACE__];
            return function () use ( $app, $plugin, $settings )
            {
                echo $plugin->google( $settings['id'], \api\Util::host() );
            };
        };

        $this->settings = json_decode( $app->config('analytics'), true );

        if( $this->settings && array_key_exists( 'id', $this->settings ) ){
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