<?php

namespace api\Controller;

class CollectionController extends \api\Controller\PageController
{
    public static function getAll( $depth=null, $type='page', $status_not='trash' )
    {
        return parent::getAll( $depth, 'collection', $status_not );
    }

    public static function getAll_as_array( $ref_id_only = true, $depth=null, $type='page', $status_not='trash' )
    {
        return parent::getAll_as_array(  $ref_id_only, $depth, 'collection',$status_not );
    }

    protected function _get( $id, $type='page', $param=array()  ) // returns array
    {
        $arr = parent::_get( $id, $type );
        if( $id ){ // if we aks for a page, add images too
            $arr['page']  = $this->_images( $id );
        }

        return $arr;
    }

    protected function _images( $id, $iid=0 ) // returns array
    {
        $page = \Model::factory('Page')->find_one( $id );
        if( !$iid ) return $page->children()->where('type', 'attachment')->find_array();
        else {
            return $arr = $page->children()->find_one($iid)->as_array();
        }
    }

    public function images( $id, $iid=0 ) // output json
    {
        $this->render( 200, $this->_images( $id, $iid ) );
    }

    public function upload( $id=0, $options=array() ) {

        //if( !$id ) throw new \ResourceNotFoundException( "Upload needs parent id" );
        if( $_FILES ) {
            $options = array_merge( $this->app->config('upload'), $options );
            $folder = isset($options['dir']) ? $options['dir'] : (date('Y').'/' . date('Y-m').'/' . date('Y-m-d').'/');
            // i am not a fan of this date folder, maybe use the page's label or page date instead?

            $arr = \services\Upload::send( $_FILES['file'], $folder, $options );

            $image= \Model::factory('Page')->where('template',$arr['file']);
            if( $image->count()==0 ) {
                $this->_create(array(
                    'type' => 'attachment',
                    'parent' => $id,
                    'status' => 'inherit',
                    'label' => $arr['label'],
                    'template' => $arr['file']
                ));
            }
            else {
                $this->get( $image->find_one()->id );
            }

        }
        else {
            throw new \ResourceNotFoundException( "No files are send for upload" );
        }
    }


    protected static function __parse__page( $page, $id_only = false ) {
        $arr = array();
        if( is_object ( $page ) ) {
            $arr = parent::__parse__page( $page, $id_only );

            $arr['page'] = array_map( function( $model ) use( $id_only ){
                return $id_only ? $model->id : parent::__parse__page( $model, $id_only );
            }, $page->children( )->where('type', 'attachment')->find_many() );
        }
        return $arr;
    }

}