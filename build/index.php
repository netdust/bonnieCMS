<?php

require('system/bootstrap.php');


require(__ROOT__.'/system/api/Routes/Admin.php');
require(__ROOT__.'/system/api/Routes/Api.php');


$app->hook('slim.before.dispatch', function () use ($app)
{

    if( !strpos($_SERVER['REQUEST_URI'],'admin') )
    {
        $param = $app->router->getCurrentRoute()->getParams();

        $uri = !isset($param['slug'])
            ? \api\Controller\PageController::getPage($app->config('homepage'))->slug
            : $param['slug'];

        $app->applyHook('render.before.page');

        $app->page = \api\CMS::get_page( $uri );

        if( $app->page == null ) {
            $app->notFound();
            exit();
        }

        $app->applyHook('render.after.page');


        $templateData = array(

            'page' => $app->page->get_array(),


            'site' => array(
                'title' => $app->config('sitename'),
                'keywords' => $app->config('keywords'),
                'description' => $app->config('description'),
                'copyright' => $app->config('copyright')
            ),

            'loc' => array(
                'url' => $app->request()->getUrl() .$app->request()->getScriptName(),
                'host' => $app->request()->getUrl(),
                'hostname' => $app->request()->getHost(),
                'root' => __ROOT__.'public/',
                'path' => $app->request()->getScriptName(),
                'theme' => __ROOT__.'public/themes/'.$app->config('theme').DS
            ),

            'lang' => $app->config('i18n.locale'),
        );

        $app->view()->appendData( $templateData );
    }

});


# -----------------------------------------------

$app->run();
