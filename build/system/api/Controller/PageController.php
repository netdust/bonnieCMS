<?php

namespace api\Controller;

class PageController extends \api\Controller\Controller
{

    /** STATIC INTERNAL API **/

    public static function getPage( $id=0, $as_array = false )
    {
        if( !$as_array ) return \Model::factory('Page')->find_one( $id );
        else {
            $c = new PageController();
            return $c->_get($id);
        }
    }

    public static function getAll( $depth=null, $type='page', $status_not='trash' )
    {
        $status_not = explode( ',', $status_not );

        if( is_numeric($depth)){
            return \Model::factory('Page')->where('parent', $depth)->where('type', $type)->where_not_in( 'status', $status_not )->find_many();
        }
        return \Model::factory('Page')->where('type', $type)->where_not_in( 'status', $status_not )->find_many();
    }

    public static function getAll_as_array( $ref_id_only = true, $depth=null, $type='page', $status_not='trash' )
    {
        return array_map(
            function( $model ) use ( $ref_id_only ) {
                return static::__parse__page(
                    $model, $ref_id_only
                );
            },
            static::getAll( $depth, $type, $status_not )
        );
    }

    public static function slug( $uri, $type=null, $include_trashed=false )
    {
        $slug = end($uri);

        $r = \Model::factory('PageTranslation')
            ->where('slug',$slug)->find_many();

        foreach( $r as $translation )
        {
            $page = $translation->page()->find_one();

            if( $page ) {
                $parent = $page->parent;
                $segments = array($slug);

                while( $parent  ) {
                    $page = \Model::factory('Page')->where( 'id', $parent )->find_one();
                    $segments[] = $page->slug;
                    $parent = $page->parent;
                }

                if( array_reverse($segments) === $uri ) {
                    $page = $translation->page()->find_one();
                    if( ( ($type!=null && $page->type == $type ) || $type==null ) && $page->status != 'trash' ) {
                        return $page;
                        break;
                    }
                }
            }
        }

        return null;
    }

    public static function get_path( $slug, $lg=1 ) {

        $translation = \Model::factory('PageTranslation')
            ->where('language_id', $lg)
            ->where('slug',$slug)
            ->find_one();

        if( $translation )
        {
            $page = $translation->page()->find_one();

            if( $page ) {
                $parent = $page->parent;
                $segments = array($slug);

                while( $parent  ) {
                    $page = \Model::factory('Page')->where( 'id', $parent )->find_one();
                    $segments[] = $page->slug;
                    $parent = $page->parent;
                }

                $segments = array_reverse($segments);
                return implode('/',$segments);
            }
        }
    }


    /** PUBLIC API **/


    /**
     * @param $id
     * @param string $type
     * @return Array
     *
     * returns page array or list of all pages
     */
    protected function _get( $id, $type='page', $param=array() ) // returns array
    {
        if( !$id ) {
            $query = \Model::factory('Page')->where('type', $type);
            $query = $this->build_query( $query, $param );
            $arr = $query->find_array();
        }
        else {
            $arr = \Model::factory('Page')->find_one( $id )->as_array();
            $arr['page_translation'] = $this->_translation( $id );
            $arr['page_meta']  = $this->_meta( $id );
        }

        return $arr;
    }



    /**
     * @param $id
     * @param int $mid
     * @return array
     *
     * returns list of metadata from page($id) or metadata with id $mid
     */
    protected function _meta( $id, $mid=0 ) // returns array
    {
        $page = \Model::factory('Page')->find_one( $id );
        if( !$mid ) {
            return array_map( function( $meta ) {
                $meta_arr = $meta->as_array();
                $meta_arr['page_meta_translation'] = $meta->translations()->find_array();
                return $meta_arr;
            }, $page->metas()->find_many() );
        }
        else {
            return $arr = $page->metas()->find_one($mid)->as_array();
        }
    }

    public function meta( $id=0, $mid=0, $param=array() ) // output json
    {
        $this->render( 200, $this->_meta( $id, $mid ) );
    }

    protected function _translation( $id, $tid=0 ) // returns array
    {
        $page = \Model::factory('Page')->find_one( $id );
        if( !$tid ) return $arr = $page->translations()->find_array();
        else {
            return $arr = $page->translations()->find_one($tid)->as_array();
        }
    }

    public function translation( $id=0, $tid=0, $param=array() ) // output json
    {
        $this->render( 200, $this->_translation( $id, $tid ) );
    }

    public function sort( $id=0, $tid=0, $param=array() )
    {
        $request = (array) json_decode($this->app->request()->getBody());
        foreach( $request as $item ) {
            $r = \Model::factory('Page')->find_one($item->id);
            if( $r ) {
                $r->sort = $item->sort;
                $r->set_expr('modified', 'NOW()');
                $r->parent = is_object($item->parent) ? $item->parent->id : $item->parent;
                $r->save();
            }
        }
        $this->render( 200, $request );
    }

    public function copy( $id=0, $tid=0, $param=array() )
    {
        $request = $this->_get( $id );
        $this->_create( $request, true, true );
    }

    public function status( $id=0, $param=array() )
    {
        $r = \Model::factory('Page')->find_one($id);
        if( $r ) {
            $r->status = $param['status'];
            $r->set_expr('modified', 'NOW()');
            $r->save();
        }
        $this->get( $id );
    }

    public function post()
    {
        $request = (array) json_decode($this->app->request()->getBody());
        $this->_create( $request, true, true, true );
    }

    public function put( $id )
    {
        $request = (array) json_decode($this->app->request()->getBody());
        $this->_create( $request, false, true, false );
    }

    public function patch( $id ) {
        $this->put($id);
    }

    public function delete( $id )
    {
        $r = \Model::factory('Page')->find_one($id);
        $r->status = 'trash';
        $r->save();

        $this->render( 200, array( 'success'=>$r ) );
    }

    public function upload( $id=0, $options=array() ) {

        if( !$id ) throw new \ResourceNotFoundException( "Upload needs parent id" );
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

    protected function _create( $data, $clear_ids=false, $sanitize=false, $isnew=true  )
    {
        if( $clear_ids ) $data = $this->_clear_ids( $data ); // clear all id's, we can use it as a post object after that
        if( $sanitize ) $data = $this->_sanitize( $data );


        if( $isnew ) {
            $r = \Model::factory('Page')->create();
            $r->set_expr('date', 'NOW()');
            unset($data['date']);
        }
        else {
            $r = \Model::factory('Page')->find_one($data['id']);
        }

        $r->set_expr('modified', 'NOW()');

        foreach( $data as $key => $value )  {
            if( !is_array( $value ) ) { // probably a relatedmodel
                $r->{$key} = $value;
            }
        }

        try {
            $r->save();
            $this->save_related( $r, $data );
        }
        catch( \Exception $e ) {
            throw new \Exception("error: ". $e->getMessage());
        }

        $this->get( $r->id );
    }

    protected function save_related( $r, $request )
    {
        foreach($request as $key => $model )
        {
            if( is_array( $model ) )
            {
                $namespace = str_replace(' ','',ucwords(str_replace('_',' ', $key)));

                foreach($model as $item )
                {
                    /* check if related_model has been synced before, otherwise create new */
                    if( isset( $item->id ) ) {
                        $relatedModel = \Model::factory($namespace)->find_one( $item->id );
                    }
                    else {
                        $relatedModel = \Model::factory($namespace)->create();
                    }

                    // set key values on related model;
                    foreach($item as $kkey => $vvalue )
                    {
                        if( $namespace == 'PageMetaTranslation' &&  is_array( $vvalue ) ){ // add lists
                            $relatedModel->{$kkey} = json_encode($vvalue);
                            $item->{$kkey} = null; // avoid object being created as model
                        }
                        else if( !is_array( $vvalue ) ) {
                            $relatedModel->{$kkey} = $vvalue;
                        }
                    }

                    // ALWAYS set foreign key;
                    $relatedModel->{$relatedModel->parent_key} = $r->id;

                    $relatedModel->save();

                    $this->save_related( $relatedModel, $item );
                }
            }
        }
    }

    protected function _clear_ids( $array ) {

        unset($array['id']);
        unset($array['page_id']);
        unset($array['meta_id']);

        foreach( $array as $key => $value )
        {
            if( is_array( $value ) ) {
                $array[$key] = $this->_clear_ids($value);
            }
        }

        return $array;
    }

    protected function _sanitize( $request ) {
        if( isset( $request['parent'] ) && is_object( $request['parent'] ) ) {
            $request['parent'] = $request['parent']->id;
        }
        if( !array_key_exists( 'parent', $request ) || is_null($request['parent']))
            $request['parent']=0;

        return $request;
    }

    protected function build_query( $query, $param ) {
        $status = explode( ',', ( isset($param['state']) ? $param['state'] : null ) );
        $sort = ( isset($param['sort']) ? $param['sort'] : null );

        if( isset($status) && $status[0]!='' ) {
            array_walk($status, function (&$item1) { $item1 = array('status'=>$item1);  });
            $query->where_any_is( $status );
        }
        if( isset($sort) ) {
            $sort = explode(',', $sort);
            foreach( $sort as $itm){
                if( strrpos($itm, '-', -strlen($itm)) !== FALSE ) {
                    $query->order_by_desc(ltrim ($itm, '-'));
                }
                else $query->order_by_asc($itm);
            }

        }
        return $query;
    }
    protected static function __parse__page( $page, $id_only = false ) {
        $arr = array();

        if( is_object ( $page ) )
        {
            $arr = $page->as_array();
            $arr['page_translation'] = array_map( function( $model ) use( $id_only ){
                return $id_only ? $model->id : $model->as_array();
            }, $page->translations()->find_many() );

            $arr['page_meta'] = array_map( function( $model ) use( $id_only )
            {
                if( $id_only ) return $model->id;
                else {
                    $arrr = $model->as_array();
                    $arrr['page_meta_translation'] = array_map( function( $mmodel )  use( $id_only ) {
                        return $id_only ? $mmodel->id : $mmodel->as_array();
                    }, $model->translations()->find_many() );

                    return $arrr;
                }

            }, $page->metas()->find_many() );
        }

        return $arr;
    }


}