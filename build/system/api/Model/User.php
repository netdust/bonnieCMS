<?php


namespace cms;

class User extends \Model {

    private $roles;

    public function pages() {
        return $this->has_many('page', 'user_id');
    }

    public function hasPermission( $perm ) {
        return $this->roles[$perm];
    }

    public function setRoles( $r = array() ) {
        $this->roles = $r;
    }

    public function getRoles() {
       return $this->roles;
    }

    public function session() {
        return $_SESSION['user'];
    }

}