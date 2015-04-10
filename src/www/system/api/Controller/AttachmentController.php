<?php

namespace api\Controller;

class AttachmentController extends \api\Controller\PageController
{
    public static function getAll( $depth=null, $type='page', $status_not='trash' )
    {
        return parent::getAll( $depth, 'attachment', $status_not );
    }

    public static function getAll_as_array( $ref_id_only = true, $depth=null, $type='page', $status_not='trash' )
    {
        return parent::getAll_as_array(  $ref_id_only, $depth, 'attachment', $status_not );
    }

    /**
     * an attachment is posted when user is adding file as metadata
     */
    public function post() // posting attachment is uploading file for a page
    {
        parent::upload( 0, '' );
    }

    public function put( $id ) {

    }

    /**
     * an attachment is uploaded when user is adding file to a collection
     * if $id = 0 then the collection is not yet saved. we mark
     * attachment as draft so we can recover it later ( status=draft && parent=0 )
     */
    public function upload( $id=0, $loc='', $options=array()  )
    {
        if( $id==0 ){
            $options['status']='draft';
        }
        parent::upload( $id, $loc, $options );
    }

}