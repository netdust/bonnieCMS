<?php

namespace api\Controller;

class UserController extends \api\Controller\Controller
{


    /**
     * Creating new user
     * @param String $name User full name
     * @param String $email User login email id
     * @param String $password User login password
     */
    public function post() {

        $this->app->applyHook( 'user.post' );

        $request = (array) json_decode($this->app->request()->getBody());
        $auth = new \services\Authentication( $request['email'], $request['password'] );

        if( $auth->email_available() ) {

            $auth->createUser(
                $request['first_name'],
                $request['last_name'],
                '',
                '',
                $request['role']
            );

            $this->render( 200, array( 'success'=>$auth->getUser()->as_array() ) );
        }

        $this->render( 200, array( 'error'=>'User already exsists' ) );

    }

    public function put( $id ) {

        $this->app->applyHook( 'user.put', $id );

        $request = (array) json_decode($this->app->request()->getBody());
        $auth = new \services\Authentication($request['email']);


        if( !$auth->email_available() ) {
            $user = $auth->getUser();
            foreach( $request as $key => $value ){
                if( $key=="password" && $value!=$user->password )
                {
                    $user->password = $auth->hash_password( $value );
                }
                else $user->{$key} = $value;
            }

            $user->save();
            $this->render( 200, array( 'success'=>$user->as_array() ) );
        }

        $this->render( 200, array( 'error'=>'User does not exsist' ) );

    }

}