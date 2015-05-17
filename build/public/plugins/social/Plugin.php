<?php

namespace plugins\social;

use Slim\Slim;
use api\controller\PageController;


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
        });// make them available for other classes */

        $hook = function ( $app ) {

            $plugin = $this->app->container->get(__NAMESPACE__);

            return function () use ( $app, $plugin )
            {

                $app->page->sitename    = $app->config('theme')->sitename;
                $app->page->image       = $plugin->search( $app->page->parent, 'image' );
                $app->page->share       = $plugin->search( $app->page->parent, 'share' );
                $app->page->seo_title   = $plugin->search( $app->page->parent, 'seo_title' );
                $app->page->url         = $app->request()->getUrl() .$app->request()->getScriptName().'/'.$app->config('language').$app->request()->getResourceUri();

                $page  = $app->page->get_array();
                $page['image'] = $app->request->getUrl() . \helpers\Util::to(  $page['image'] );

                $meta  = $plugin->facebook();

                echo $plugin->replace( $page, $meta );
            };

        };

        $script = function ( $app ) {

            $plugin = $this->app->container->get(__NAMESPACE__);

            return function () use ( $app, $plugin )
            {
                echo $plugin->facebook_embed( $this->settings->id );
            };

        };

        $body = function ( $app ) {

            $plugin = $this->app->container->get(__NAMESPACE__);

            return function () use ( $app, $plugin )
            {
                echo '<div id="fb-root"></div>';
            };

        };

        $app->hook( 'header', $hook( $app ) );



        if( isset( $this->settings ) && isset( $this->settings->id ) && $this->settings->id != ''  ){
            $app->hook( 'script', $script( $app ) );
            $app->hook( 'body', $body( $app ) );
        }

        $this->next->call();
    }

    public function facebook_embed( $id ) {
        ob_start(); ?>
<script type="text/javascript">

    // Load the SDK Asynchronously
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3&appId=<?php echo $id; ?>";
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

            return !isset( $this->settings->meta ) ? 'no value' : $this->settings->meta;
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