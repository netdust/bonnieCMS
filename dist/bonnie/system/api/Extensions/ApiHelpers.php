<?php

namespace api\Extensions;

class ApiHelpers extends \Slim\Views\TwigExtension
{
    public function getName()
    {
        return 'api';
    }

    public function getFunctions()
    {
        $options = array(
            'is_safe' => array('html')
        );

        return array(
            new \Twig_SimpleFunction('page_*', array($this, 'page_handler'), $options ),
            new \Twig_SimpleFunction('__*', array($this, 'util_handler'), $options ),
            new \Twig_SimpleFunction('_*', array($this, 'php_handler'), $options )

        );
    }

    public function php_handler( $param, $arg='' )
    {
        return call_user_func_array($param, is_array($arg)?$arg:array($arg));
    }

    public function util_handler( $param, $arg='' )
    {
        return call_user_func_array(array('\helpers\Util', $param), is_array($arg)?$arg:array($arg));
    }

    public function page_handler( $param, $arg='' )
    {
        $param = str_replace( '_', '-', $param );
        return call_user_func_array(array('\helpers\Page', $param), is_array($arg)?$arg:array($arg));
    }


}