<?php

namespace plugins\youtube;

use Slim\Slim;


class Plugin extends \Slim\Middleware {

    /**
     * @param array $config
     */

    protected $settings;

    function __construct( $config ) {
        $this->settings = $config;
    }

    public function call()
    {
        $app = Slim::getInstance();
        $this->app->container->singleton(__NAMESPACE__, function () {
            return $this;
        });

        $script = function ( $app ) {
            $plugin = $this->app->container->get(__NAMESPACE__);
            return function () use ( $app, $plugin )
            {
                echo $plugin->youtube_embed();
            };
        };

        $app->hook( 'script', $script( $app ) );
        $this->next->call();
    }

    public function youtube_embed( ) {
        ob_start(); ?>
        <script type="text/javascript">
            window.onYouTubeIframeAPIReady = function() {
                //ntdst.api.youtube = YT.Player;
            };
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        </script>
        <?php return ob_get_clean();
    }

}

return __NAMESPACE__;

