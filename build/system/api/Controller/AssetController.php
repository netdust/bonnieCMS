<?php

namespace api\Controller;

class AssetController extends \api\Controller\PageController
{

    public static function getAll( $ref_id_only = true )
    {
        return array_map(
            function($model ) use ($ref_id_only) {
                return self::__parse__page(
                    $model, $ref_id_only
                );
            },
            \Model::factory('Page')->where('type', 'attachement')->where_not_equal( 'status', 'trash' )->find_many()
        );
    }


    public function upload( $id )
    {
        if (!empty($_FILES)) {

            $folder = $this->app->config('upload.folder').'/'.date('Y').'/' . date('Y-m').'/' . date('Y-m-d').'/';

            if ( !file_exists( $folder ) )
            {
                if(!mkdir($folder, 0777, true)){
                    $this->app->halt(400, "can't create folder" );
                };
            }

            \Slipp\Helper\Upload::send( $_FILES['file'], $folder, array('hash'=>$id!=0?true:false) );

            if( \Slipp\Helper\Upload::$info['error'] != '' ) {
                $this->app->halt(400, \Slipp\Helper\Upload::$info['error'] );
            }
            else  {

                $this->create_asset( $id, $_FILES['file']['name'], \Slipp\Helper\Upload::$info['name'] );

            }
        }
    }

    public function image() {
        $request = (array) json_decode($this->app->request()->getBody());


    }

    private function create_asset( $id, $name, $template )
    {
        $r = \Model::factory('Page')->create();
        $r->type = 'attachment';
        $r->parent = $id;
        $r->status = 'inherit';
        $r->label  = $name;
        $r->template  = $template;

        $r->set_expr('date', 'NOW()');
        $r->set_expr('modified', 'NOW()');

        $r->save();

        $r = \Model::factory('Page')->find_one($id);
        $this->app->status( 200 );
        $this->render( $this->__parse__page( $r, false  ) );
    }

    protected static function __parse__page( $page, $id_only = false ) {
        $arr = parent::__parse__page( $page, $id_only );
        $arr['page'] = array_map( function( $model ) use( $id_only ){
            return $id_only ? $model->id : $model->as_array();
        }, $page->children( )->where('type', 'attachment')->find_many() );
        return $arr;
    }


}