<?php

namespace cms;

class Page extends \Model
{

    public $parent_key = 'parent';

    public static function is_type($orm, $key) {
        return $orm->where('type', $key);
    }

    public static function has_status($orm, $key) {
        return $orm->where('status', $key);
    }

    public function __get($name) {

        $r = parent::__get( $name );
        if( isset($r) ) {
            return $r;
        }

        try {
            if( $trans = $this->translations()->filter('language')->find_one() ) {
                $r = $trans->{$name};
                if( isset($r) ) {
                    return $r;
                }
            }
        }catch (Exception $e) {
        }


        try {
            $r = $this->metas()->where('key',$name);
            if( $r->count() > 0 ) {
                return $r->find_one()->value;
            }
        }catch (Exception $e) {

        }

        return null;
    }

    public function user() {
        return $this->belongs_to('User', 'user_id', 'id');
    }

    public function metas() {
        return $this->has_many('PageMeta', 'page_id');
    }

    public function translation() {
        return $this->translations()->filter('language');
    }

    public function translations() {
        return $this->has_many('PageTranslation', 'page_id');
    }

    public function hasChildren( ) {
        return $this->children( )->count() > 0;
    }

    public function child( $index ) {
        $arr =  children( )->as_array();
        return $arr[$index];
    }

    public function children( ) {
        return \Model::factory('Page')->where('parent',$this->id)->where_not_equal( 'status', 'trash' );
    }

    public function relative() {
        $segments = array( $this->slug );
        $parent = $this->parent;

        while( $parent != 0 ) {
            $page = \Model::factory('Page')->where( 'id', $parent )->find_one();
            $segments[] = $page->name;
            $parent = $page->parent;
        }

        return implode('/', array_reverse($segments));
    }

    /* return translated array */
    public function get_array()
    {
        $arr = $this->as_array();

        if( $trs = $this->translation()->find_one() ){
            $arr['slug'] = $trs->slug;
            $arr['title'] = $this->markdown($trs->description);
            $arr['content'] = $this->markdown($trs->content);
        }

        $arr['translation'] = array_map( function( $trans ) {
            return $trans->as_array();
        }, $this->translations()->find_many() );

        $metas = $this->metas()->find_many();
        foreach( $metas as $meta ) {
            $key = $meta->key;
            $value = $meta->value;
            $arr[$key] = $this->markdown($value);
        }

        $arr['children'] = array_map( function( $child ) {
            return $child->as_array();
        }, $this->children()->find_many() );
        $arr['num_children'] = count( $arr['children'] );

        return $arr;
    }

    public function markdown( $str ) {
        return preg_replace('!^<p>(.*?)</p>$!i', '$1',  \Michelf\Markdown::defaultTransform($str));
    }

}