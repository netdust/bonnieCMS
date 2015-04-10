<?php

namespace api\Controller;

class JsonController extends \api\Controller\Controller
{

    public $data_array = array();
    protected $file = "public/data/admin.json";

    public function __construct()
    {
        $this->app = \Slim\Slim::getInstance();
    }

    public function get( $id=0, $type='page', $param=array() )
    {
        $this->render( 200, $this->data_array );
    }

    public function post(  )
    {
        $request = (array) json_decode($this->app->request()->getBody());
        $m = $this->search( 'label', $request['label'] );
        if( count($m)>0 ) {
            $this->put_data( $m, $request );
        }
        else
            $this->post_data( $request );
    }

    public function put(  )
    {
        $this->post();
    }

    protected function init_data( $key='' ) {
        $this->load();
        $this->data_array = (array) $this->app->config('theme')->{$key};
    }

    protected function post_data( $data ) {
        $this->data_array[] = (object) $data;
        $this->save();
    }

    protected function put_data( $model, $data ) {
        $this->data_array = array_replace( $this->data_array, array( max(array_keys($model)) => (object) $data ));
        $this->save();
    }

    protected function load( ) {
        $json = file_get_contents(__ROOT__.$this->file);
        $this->app->config('theme', json_decode($json));
    }

    protected function save( ) {
        file_put_contents(__ROOT__.$this->file, json_encode($this->app->config('theme')));
        $this->render( 200, $this->data_array );
    }

    protected function search( $key, $value, $index=true ) {

        $results = array_filter( $this->data_array, function($item) use ( $key, $value ){
            return $item->{$key} == $value;
        } );

        return $index ? array_values($results) : $results;
    }

}