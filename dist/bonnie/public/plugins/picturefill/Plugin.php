<?php

namespace plugins\picturefill;

use Slim\Slim;


class Plugin extends \Slim\Middleware {

    /**
     * @param array $config
     */

    protected $settings;

    function __construct( $config ) {
        $this->settings = $config;
        $this->addRoutes();
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
                //echo $plugin->script_embed( );
            };
        };

        //$app->hook( 'script', $script( $app ) );
        $this->next->call();
    }

    public function script_embed( ) {
        ob_start(); ?>
        <script type="text/javascript" src=""></script>
        <?php return ob_get_clean();
    }

    protected function addRoutes() {
        $app = Slim::getInstance();
        $app->get('/img/:parts+', array(&$this, 'actionServeImage'))
            ->name('img');
    }

    public function actionServeImage( $parts )
    {

        $app = Slim::getInstance();
        // Grab the filename
        $filename = array_pop($parts);
        // Grab the derivative
        $derivative = array_shift($parts);
        // Expand the paths
        $path = implode("/", $parts);
        // assemble the destination path
        $destination_path = __ROOT__.$this->settings->folder . $derivative . '/' . $path .'/';
        // check if image exists already, use cached  version
        if (file_exists($destination_path.$filename)) {
            $image = new \services\Images($destination_path.$filename);
            $app->response->headers->set('Content-Type', 'image/jpeg');
            imagejpeg($image->image);
            exit;
        }
        // Create the directory, if required
        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0777, true); // important: set recursive to true
        }
        // Now get the source path
        $source_path = __ROOT__.$this->settings->folder.$path.'/';
        // get the specs from the config
        $specs = $this->settings->sizes->{$derivative};
        // Create a new Imagick object
        $path_info = pathinfo($filename);
        $ext = strtoupper($path_info['extension']);
        $image = new \services\Images($source_path.$filename);
        $image->thumb(
            (isset($specs->width)) ? $specs->width : 0,
            (isset($specs->height)) ? $specs->height : 0
        );
        $image->save( $destination_path,  $filename, $ext, $this->settings->overwrite,  $this->settings->compression );

        // set the headers; first, getting the Mime type
        $app->response->headers->set('Content-Type', 'image/jpeg');
        // Get the file extension, so we know how to output the file
        // display image
        imagejpeg($image->image);
        // output the image
        // $image->output();
        // Free up the image resources
        //$image->destroy();

    }

}

return __NAMESPACE__;

