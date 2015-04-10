<?php

namespace data\plugins\social;

use Slim\Slim;
use \controller\PageController;


class Plugin extends \Slim\Middleware {

    /**
     * @param array $config
     */

    protected $settings;

    function __construct() {
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

                $app->page->sitename    = $app->config('sitename');
                $app->page->image       = $plugin->search( $app->page->parent, 'image' );
                $app->page->share       = $plugin->search( $app->page->parent, 'share' );
                $app->page->seo_title   = $plugin->search( $app->page->parent, 'seo_title' );
                $app->page->url         = $app->request()->getUrl() .$app->request()->getScriptName().'/'.$app->config('i18n.locale');

                $page  = $app->page->get_array();
                $page['image'] = $app->request->getUrl() . \Slipp\Helper\Util::to( 'data/upload/' . $page['image'] );

                $meta  = $plugin->facebook();

                echo $plugin->replace( $page, $meta );
            };

        };

        $script = function ( $app ) {

            $plugin = $app->plugins[__NAMESPACE__];

            return function () use ( $app, $plugin )
            {
                echo $plugin->facebook_embed( $this->settings['id'] );
            };

        };

        $body = function ( $app ) {

            $plugin = $app->plugins[__NAMESPACE__];

            return function () use ( $app, $plugin )
            {
                echo '<div id="fb-root"></div>';
            };

        };

        $app->hook( 'header', $hook( $app ) );

        $this->settings = json_decode( $app->config('social'), true );

        if( $this->settings && array_key_exists( 'id', $this->settings ) && $this->settings['id'] != ''  ){
            $app->hook( 'script', $script( $app ) );
            $app->hook( 'render.after.body', $body( $app ) );
        }

        $this->next->call();
    }

    public function facebook_embed( $id ) {
        ob_start(); ?>
<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '<?php echo $id; ?>',
            xfbml      : true,
            version    : 'v2.2'
        });
    };
    // Load the SDK Asynchronously
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
        <?php return ob_get_clean();
    }

    public function facebook() {
        ob_start(); ?>

    <!-- Open Graph data -->
    <meta property="og:title" content="{{seo_title}}" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{url}}" />
    <meta property="og:image" content="{{image}}" />
    <meta property="og:description" content="{{share}}" />
    <meta property="og:site_name" content="{{sitename}}" />

        <?php return ob_get_clean();
    }

    public function search( $id, $meta ) {

        if( $id != 0 && $page = PageController::getPage($id) ) {
            $share =  $page->as_array();
            if( array_key_exists($meta, $share) ) {
                return $share[$meta];
            }
            else return $this->search( $share['parent'], $meta );
        }
        else {

            return !array_key_exists( $meta, $this->settings ) ? 'no value' : $this->settings[$meta];
        }

    }

    public function replace( $variables, $string ) {
        foreach($variables as $key => $value){
            if( !is_array($value) )
                $string = str_replace('{{'.$key.'}}', $value, $string);
        }
        echo $string;
    }

}

return __NAMESPACE__;