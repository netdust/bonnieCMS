<?php

namespace view;

namespace api\Extensions;

class Twig_Extension_ApiHelpers extends \Slim\Views\TwigExtension
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

        return array_merge( parent::getFunctions(), array(

            new \Twig_SimpleFunction('util_*', array($this, 'util_handler'), $options ),
            new \Twig_SimpleFunction('page_*', array($this, 'page_handler'), $options ),
            new \Twig_SimpleFunction('__*', array($this, 'util_handler'), $options ),
            new \Twig_SimpleFunction('*', array($this, 'cms_handler'), $options )

        ));
    }

    public function util_handler( $param, $arg='' )
    {
        return call_user_func_array(array('\api\Util', $param), is_array($arg)?$arg:array($arg));
    }

    public function cms_handler( $param, $arg='' )
    {
        return call_user_func_array(array('\api\CMS', $param), is_array($arg)?$arg:array($arg));
    }

    public function page_handler( $param, $arg='' )
    {
        return call_user_func_array(array('\api\Page', $param), is_array($arg)?$arg:array($arg));
    }

}