<?php

namespace api\Controller;

class UserController extends \api\Controller\Controller
{

    protected function _get( $id ) // returns array
    {
        if( !$id ) $arr = \Model::factory('User')->find_array();
        else {
            $arr = \Model::factory('User')->find_one( $id )->as_array();
        }

        return $arr;
    }

    public function get( $id=0  ) // output json
    {
        $this->render( 200, $this->_get( $id ) );
    }


    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function post() {

        $status = 200;
        $response = array();
        $request = (array) json_decode($this->app->request()->getBody());

        // First check if user already existed in db
        if ( !$this->ifUserExists( $request['email'] ) )
        {
            // Generating password hash
            $request['password'] = $this->generatePassword( $request['password'] );

            // Generating API key
            $request['api_key'] = $this->generateApiKey();

            // Generating Object
            $model = \Model::factory('User')->create();
            foreach( $request as $key => $value ){
                $model->{$key} = $value;
            }

            // Check for successful insertion
            if ( $model->save() ) {
                // User successfully inserted
                $response = $model->as_array();
            } else {
                // Failed to create user
                $status = 400;
                $response = array('error'=>'oh oh');
            }
        } else {
            // User with same email already existed in the db
            $status = 400;
            $response = array('error'=>'no 2 alike');
        }

        $this->app->status( $status );
        $this->render( $response );

    }

    public function put( $id ) {

        $status = 200;
        $response = array();
        $request = (array) json_decode($this->app->request()->getBody());

        // First check if user already existed in db
        if ( $this->ifUserExists( $request['email'] ) ) {

            // Generating Object
            $model = \Model::factory('User')->where('email', $request['email'] )->find_one();
            foreach( $request as $key => $value ){
                if( $key=="password" && $value!=$model->password ) {
                    $model->password = $this->generatePassword( $value );
                }
                else $model->{$key} = $value;
            }

            // Check for successful insertion
            if ( $model->save() ) {
                // User successfully inserted
                $response =  $model->as_array();
            } else {
                $status = 400;
                $response = array('error'=>'oh oh');
            }
        } else {
            $status = 400;
            $response = array('error'=>'no user exists, ' . $this->ifUserExists( $request['email'] ) );
        }

        $this->app->status( $status  );
        $this->render( $response );
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function ifUserExists($email) {
        $model = \Model::factory('User')->where('email', $email);
        return $model->count() > 0;
    }

    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $model = \Model::factory('User')->where('email', $email)->find_one();
        if ( $model ) {
            return $model->as_array();
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user api key
     * @param String $user_id user id primary key in user table
     */
    public function getApiKeyById($user_id) {
        $model = \Model::factory('User')->find_one($user_id);
        if ( $model ) {
            return $model->api_key;
        } else {
            return NULL;
        }
    }

    /**
     * Fetching user id by api key
     * @param String $api_key user api key
     */
    public function getUserId($api_key) {
        $model = \Model::factory('User')->where('api_key', $api_key)->find_one();
        if ( $model ) {
            return $model->id;
        } else {
            return NULL;
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key
     * @return boolean
     */
    public function isValidApiKey($api_key) {
        return ( $this->getUserId($api_key) != NULL );
    }
    /**
     * Generating random Unique MD5 String for user Api key
     */
    private function generateApiKey() {
        return md5(uniqid(rand(), true));
    }

    private function generatePassword( $psw ) {
        return \Slipp\Helper\Crypt::make( $psw );
    }
}