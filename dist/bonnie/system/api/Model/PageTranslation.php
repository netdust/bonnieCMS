<?php

namespace cms;

class PageTranslation extends \Model {

    public $parent_key = 'page_id';

    public static function language($orm) {
        return $orm->where('language_id', (isset( $_SESSION['language.id'] ) ? $_SESSION['language.id'] : 1));
    }

    public function page() {
        return $this->belongs_to('Page', $this->parent_key);
    }

}