<?php

function locate_template( $type='page', $ext='.html') {



    $tpl = '';
    $app = \Slim\Slim::getInstance();
    $loader = $app->view->getInstance()->getLoader();


    $templates = array("{$type}-{$app->page->id}", "{$type}-{$app->page->label}", "{$app->page->template}",  "{$app->page->type}", $type);

    foreach ( $templates as $template ) {
        if( $loader->exists($template.$ext) ) {
            $tpl = $template.$ext;
            break;
        }
    }

    return $tpl;
}

function render( $template, $data = array() ) {
    $app = \Slim\Slim::getInstance();
    $app->render( $template, $data );
}

function display( $template, $data = array() ) {
    $app = \Slim\Slim::getInstance();
    $app->view->getInstance()->display( $template, $data );
}

function template_file($file, $vars) {
    ob_start();
    extract($vars);
    include($file);
    return ob_get_contents() . (ob_end_clean() ? "" : "");
}
?>