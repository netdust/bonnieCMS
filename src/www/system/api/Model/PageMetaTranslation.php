<?php

namespace cms;

class PageMetaTranslation extends \Model {

    public $parent_key = 'meta_id';

    public static function language($orm) {
        return $orm->where('language_id', (isset( $_SESSION['i18n.localeId'] ) ? $_SESSION['i18n.localeId'] : 1));
    }


    public function meta() {
        return $this->belongs_to('Meta', $this->parent_key);
    }


}