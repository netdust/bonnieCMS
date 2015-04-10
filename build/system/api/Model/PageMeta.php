<?php

namespace cms;

class PageMeta extends \Model {


    public $parent_key = 'page_id';


    /**
     * @param $name
     * @return mixed translated value of the key/value column OR table attribute value
     */
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
            print_r( $e );
        }

    }

    public function val() {

    }

    public function remove() {
        $translations = $this->translations()->find_many();
        foreach( $translations as $translation ) {
            $translation->delete();
        }
        $this->delete();
    }

    public function page() {
        return $this->belongs_to('Page', $this->parent_key);
    }

    public function translations() {
        return $this->has_many('PageMetaTranslation', 'meta_id');
    }

}