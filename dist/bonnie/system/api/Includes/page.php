<?php

use \api\Controller\PageController;
use \helpers\Page;

function get_url( $id=0 ) {
    $app = Slim\Slim::getInstance();

    if( $lang = $app->config('language') != '' ) {
        return $app->request()->getUrl() .$app->request()->getScriptName().'/'.$lang. '/'. get_relative( $id );
    }
}

function get_slug( $id=0 ) {
    if( $id==0 ) {
        $app = Slim\Slim::getInstance();
        return $app->request()->getResourceUri();
    }
    $page = api\Controller\PageController::getPage( $id );
    return $page->slug;
}

/*
function get_pages( $key, $value ) {

   $r= ORM::for_table('cms_page')->distinct()
       ->select('cms_page.*')
       ->join('cms_page_meta', array(
           'cms_page.id', '=', 'cms_page_meta.page_id'
       ))
       ->join('cms_page_meta_translation', array(
           'cms_page_meta.id', '=', 'cms_page_meta_translation.meta_id'
       ))
       ->where_equal('cms_page_meta.key', $key )
       ->where_raw("FIND_IN_SET($value, cms_page_meta_translation.value)")
       ->find_many();


    print_r($r);
    print_r(ORM::get_last_query());

}*/

function get_relative( $id=0 ) {

    return Page::relative( $id );

}

function is_active() {

}

function is_published() {

}

