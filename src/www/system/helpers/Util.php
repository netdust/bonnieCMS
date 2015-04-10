<?php

namespace helpers;

class Util {

    public static function url() {
        $app = \Slim\Slim::getInstance();
        return  $app->request->getUrl();
    }

    public static function host() {
        $app = \Slim\Slim::getInstance();
        return  $app->request->getHost();
    }

    public static function root() {
        $app = \Slim\Slim::getInstance();
        return  $app->request->getRootUri();
    }

    public static function path() {
        $app = \Slim\Slim::getInstance();
        return  $app->request->getPath();
    }

    public static function imagepath($item) {
        return  self::_path('img',$item);
    }

    public static function csspath($item) {
        return  self::_path('css',$item);
    }

    public static function jspath($item) {
        return  self::_path('js',$item);
    }

    public static function uploadpath($item) {
        return  self::to( 'data/upload/' . $item );
    }

    protected  static function _path($asset, $item) {
        $app = \Slim\Slim::getInstance();
        return  self::to( 'public/themes/' . $app->config('theme'). '/' . $asset. '/' . $item );
    }

    public static function to($uri) {
        if(strpos($uri, '://')) return $uri;
        $app = \Slim\Slim::getInstance();
        $base = $app->request()->getRootUri();
        return $base. '/' . ltrim($uri, '/');
    }

    public static function uri($uri) {
        if(strpos($uri, '://')) return $uri;
        $app = \Slim\Slim::getInstance();
        return $app->request()->getUrl(). '/' . self::to($uri);
    }



    /**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    public static function echo_json($response, $status_code=200) {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);
        // setting response content type to json
        $app->contentType('application/json');
        echo ($response);
        exit();
    }


    /**
     * Validating email address
     */
    public static function validateEmail($email) {
        $app = \Slim\Slim::getInstance();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["error"] = true;
            $response["message"] = 'Email address is not valid';
            self::echo_json(400, $response);
            $app->stop();
        }
    }


    public static function key_exists($source, $key) {
        return isset($source[$key]) || array_key_exists($key, $source);
    }

    public static function get($source, $key, $default) {
        return self::key_exists($source, $key) ? $source[$key] : $default;
    }


}
