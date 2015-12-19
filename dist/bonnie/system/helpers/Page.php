<?php

namespace helpers;

use \Slim\Slim;
use \api\Controller\PageController;

class Page
{

    static function __callStatic($method, $params=null)
    {
        $app = \Slim\Slim::getInstance();

        if( $method=='page' ) return $app->page;
        else if( isset( $params ) ){
                $p = self::_findPage( $params[0] );
                if($p) $page = $p;
            }


        if( !isset( $page ) ) {
            $page = $app->page;
        }

        if( isset( $page ) ){

            if( method_exists( $page, $method ) ) {
                $v = call_user_func(array($page, $method) );
            }
            else {
                $v = $page->{$method};
            }

            if($v) return $v;
            return null;
        }

        return null;
    }

    static function relative( $page=null ) {

        if( is_numeric( $page ) ) {
            $p = self::_findPage( $page );
        }
        else {
            $Slim = Slim::getInstance();
            $p = $Slim->page;
        }

        return $p->relative();

    }

    static function title( $page=null ) {

        if( is_numeric( $page ) ) {
            $p = self::_findPage( $page );
        }
        else {
            $Slim = Slim::getInstance();
            $p = $Slim->page;
        }

        if($p) {
            return $p->description;
        }

        return '';

    }

    static function content( $page=null ) {

        if( is_numeric( $page ) ) {
            $p = self::_findPage( $page );
        }
        else {
            $Slim = Slim::getInstance();
            $p = $Slim->page;
        }

        if($p) {
            return $p->content;
        }

        return '';

    }

    static function children( $page )
    {

        $children = null;
        if( is_numeric( $page ) ) {
            $p = self::_findPage( $page );
            if($p) $page = $p;
        }
        else {
            $Slim = Slim::getInstance();
            $page = $Slim->page;
        }

        if( $page->hasChildren() ) {
            $children = $page->children()->find_many();
            \helpers\Util::sort_pages($children,'sort');
            return array_map(
                function($model) {
                    $m = $model->get_array();
                    if($model->hasChildren())
                    {
                        $m['children'] = self::children( $model->id );
                    }
                    return $m;
                }, $children

            );
        }

        return null;
    }

    static function isActive( $page ) {
        $Slim = Slim::getInstance();
        return $page->slug == $Slim->page->slug;
    }

    static function isHome( ) {
        $Slim = Slim::getInstance();
        return $Slim->page->slug == $Slim->config('homepage');
    }

    static function isDraft() {
        return self::status() == 'draft';
    }

    static function isPrivate() {
        return self::status() == 'private';
    }

    static function isPublished() {
        return self::status() == 'published';
    }

    private static function _findPage( $id ) {
        $p = PageController::getPage( $id );
        if($p) return $p;
        return null;
    }

}