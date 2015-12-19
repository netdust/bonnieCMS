<?php
/**
 * Created by JetBrains PhpStorm.
 * User: netdust
 * Date: 04/09/13
 * Time: 19:24
 * To change this template use File | Settings | File Templates.
 */

namespace helpers;

class Html {

    private static function element($name, $content = '', $attributes = null) {
        $short = array('img', 'input', 'br', 'hr', 'frame', 'area', 'base', 'basefont',
            'col', 'isindex', 'link', 'meta', 'param');

        if(in_array($name, $short)) {
            if($content) $attributes['value'] = $content;

            return '<' . $name . static::attributes($attributes) . '>';
        }

        return '<' . $name . static::attributes($attributes) . '>' . $content . '</' . $name . '>';
    }

    private static function attributes($attributes) {
        if(empty($attributes)) return '';

        if(is_string($attributes)) return ' ' . $attributes;

        foreach($attributes as $key => $val) {
            $pairs[] = $key . '="' . $val . '"';
        }

        return ' ' . implode(' ', $pairs);
    }

    public static function input($title, $attributes = array()) {
        return static::element('input', $title, $attributes);
    }

    public static function link($uri, $title = '', $attributes = array()) {
        if(strpos('#', $uri) !== 0) $uri = $uri;

        if($title == '') $title = $uri;

        $attributes['href'] = $uri;

        return static::element('a', $title, $attributes);
    }

    public static function mailto( $email, $title='contact' ) {
        return self::link( 'mailto:'.$email, $title,  array('target'=>'top') );
    }
}