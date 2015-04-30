<?php

require('system/bootstrap.php');


require(__ROOT__.'/system/api/Routes/Admin.php');
require(__ROOT__.'/system/api/Routes/Api.php');


$app->hook('slim.before.dispatch', function () use ($app)
{

    if( !strpos($_SERVER['REQUEST_URI'],'cms') )
    {
        $app->applyHook('before.page');


        $param = $app->router->getCurrentRoute()->getParams();

        $uri = isset($param['slug']) ? $param['slug'] : \api\Controller\PageController::getPage($app->config('theme')->home)->slug;
        $app->page = \api\Controller\PageController::slug( explode('/', $uri) );

        if( $app->page == null ) {
            $app->notFound();
            exit();
        }

        $app->applyHook('after.page');


        $templateData = array(

            'page' => $app->page->get_array(),

            'site' => $app->config('theme'),

            'loc' => array(
                'url' => $app->request()->getUrl() .$app->request()->getScriptName(),
                'host' => $app->request()->getUrl(),
                'hostname' => $app->request()->getHost(),
                'root' => __ROOT__.'public/',
                'path' => $app->request()->getScriptName(),
                'theme' => __ROOT__.'public/themes/'.$app->config('theme')->theme.DS
            ),

            'lang' => $app->config('i18n.locale'),

        );

        $app->applyHook('before.data');

        $app->view()->appendData( $templateData );
    }

});


# -----------------------------------------------

$app->run();
