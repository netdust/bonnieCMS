<?php

namespace api\Controller;

class TemplateController extends \api\Controller\JsonController
{

    public function __construct( ) {
        $this->app = \Slim\Slim::getInstance();
        $this->init_data('templates');
    }

    public function names()
    {
        foreach(  $this->data_array as $template ){
            $arr[] = $template->name;
        }
        return $arr;
    }

    public function init_data( $key='' )
    {
        parent::init_data($key);
        $this->find_templates();
    }

    public function find_templates() {

        $theme = $this->app->config('theme')->theme; //\Model::factory('Config')->where('key', 'theme' )->find_one();

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__ROOT__ .'public/themes/'.$theme)) as $filename)
        {

            if($filename->isFile() and $filename->isReadable() &&  pathinfo($filename->getFilename(), PATHINFO_EXTENSION)=='html' ) {

                $data = file_get_contents( $filename->getPathname() );

                $defaults =  array(
                    'parent' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $this->_getParent( $data ) ),
                    'name' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename->getFilename() ),
                    'file' => $filename->getFilename(),
                    'meta' => array(),
                );

                $settings = $this->search( 'name', $defaults['name']);
                if( count($settings) == 0 ) {
                    $this->data_array[] = (object) $defaults;
                }
            }

        }

    }

    private function _getParent($str) {
        // find all block starting tags
        preg_match('/\{%\s+extends\s+"(.*?)"\s+%\}/', $str, $matches);
        return count($matches)>0 ? $matches[1]:'';
    }

}