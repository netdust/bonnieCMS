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
            /*new \Twig_SimpleFunction('page_*', array($this, 'page_handler'), $options ),*/
            new \Twig_SimpleFunction('__*', array($this, 'util_handler'), $options ),
            new \Twig_SimpleFunction('&*', array($this, 'util_handler'), $options )
        );
    }

    public function util_handler( $param, $arg='' )
    {
        return call_user_func_array(array('\helpers\Util', $param), is_array($arg)?$arg:array($arg));
    }


}