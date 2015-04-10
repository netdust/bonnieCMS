<?php

namespace api\Controller;

class TagController extends \api\Controller\Controller
{

    public function find( $tag, $key="tags" ) { // search pages table with pages that has a certain tag
        $r= \ORM::for_table('cms_page')->distinct()
            ->select('cms_page.*')
            ->join('cms_page_meta', array(
                'cms_page.id', '=', 'cms_page_meta.page_id'
            ))
            ->join('cms_page_meta_translation', array(
                'cms_page_meta.id', '=', 'cms_page_meta_translation.meta_id'
            ))
            ->where_equal('cms_page_meta.key', $key)
            ->where_raw("FIND_IN_SET($tag, cms_page_meta_translation.value)")
            ->find_many();

        print_r($r);
        print_r(\ORM::get_last_query());
    }
}