<?php

namespace cms;

class Config extends \Model {

    public static function save_data( $data ) {

        foreach( $data as $key => $value ){
            $m = \Model::factory('Config')->where('key', $key )->find_one();
            if( $m ) {
                $m->value = $value;
                $m->save();
            }
        }

        return \Model::factory('Config')->find_many();
    }

 }