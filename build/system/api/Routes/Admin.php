<?php

# middleware
///$app->add( new \api\Middleware\CsrfGuard() );


$app->group(
    '/cms',
    function () use ($app) {

        $email_template = function($file, $vars=array()) {
            ob_start();
            extract($vars);
            include($file);
            return ob_get_contents() . (ob_end_clean() ? "" : "");
        };

        $is_time_locked_out = function () use ($app){
            //return function () use ($app) {
                $identity = $app->request()->post('email');
                //return $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'ion_auth');
                //logic to lock an account if they have tried logging in 25 times within 3 minutes
            //};
        };

        $authenticate = function () use ($app) {

            if( !isset($_SESSION['user']) || !array_key_exists('username', $_SESSION['user'] ) ) {
                try {
                    $auth = new \services\Authentication(); // check if we can login using a cookie
                    $auth->login();
                    return true;
                } catch( \services\AuthenticationException $e){
                    $r = $app->request()->getPathInfo();
                    $app->flash('message', 'Login required');
                    $app->redirect( $app->request->getRootUri(). '/cms/login' .( $r=='/cms/login' ? '' : '?r='.$r ) );
                }
            }

            return true;
        };

        $app->get("/logout", function () use ($app) {

            $auth = new \services\Authentication($_SESSION['user']['email']);
            $auth->logout();

            $app->flash('message', 'Logout successful');
            $app->redirect($app->request->getRootUri(). '/cms/login');

        });


        $app->get("/login", function () use ($app) {
            if ($app->request()->get('r') && $app->request()->get('r') != '/logout' && $app->request()->get('r') != '/login') {
                $_SESSION['urlRedirect'] = $app->request()->get('r');
            }
            $app->render('\Authentication\Login.php');
        });

        $app->post("/login", function () use ($app) {

            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $remember = $app->request()->post('remember');

            $auth = new \services\Authentication($email, $password, $remember);

            try {
                $auth->login( );

                if (isset($_SESSION['urlRedirect'])) {
                    $urlRedirect = $_SESSION['urlRedirect'];
                    unset( $_SESSION['urlRedirect'] );
                    $app->redirect( $app->request->getRootUri(). $urlRedirect );
                }
                else $app->redirect($app->request->getRootUri(). '/cms/');

            } catch( \services\AuthenticationException $e ) {
                $app->flash('message', $e->getMessage());
                $app->redirect($app->request->getRootUri(). '/cms/login');
            }

        });

        $app->get("/createuser", function () use ($app) {

            $flash = $app->view()->getData('flash');
            $error = $first_name = $last_name = $company = $phone = $email = '';
            //get groups
            $groups = \ORM::for_table('cms_groups')->find_array();
            if (isset($flash['error'])) {
                $error = $flash['error'];
            }
            if (isset($flash['first_name'])) {
                $first_name = $flash['first_name'];
            }
            if (isset($flash['last_name'])) {
                $last_name = $flash['last_name'];
            }
            if (isset($flash['company'])) {
                $company = $flash['company'];
            }
            if (isset($flash['phone'])) {
                $phone = $flash['phone'];
            }
            if (isset($flash['email'])) {
                $email = $flash['email'];
            }
            if (isset($flash['email'])) {
                $email = $flash['email'];
            }
            $app->render('Authentication/CreateUser.php', array( 'error' => $error, 'first_name' => $first_name, 'last_name' => $last_name, 'company' => $company,'phone' => $phone,'email' => $email, 'groups' => $groups));
        });

        $app->post("/createuser",  function () use ($app)
        {
            $first_name = $app->request()->post('first_name');
            $last_name = $app->request()->post('last_name');
            $company = $app->request()->post('company');
            $phone = $app->request()->post('phone');
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $group = $app->request()->post('group');

            $app->flash('first_name', $first_name);
            $app->flash('last_name', $last_name);
            $app->flash('company', $company);
            $app->flash('phone', $phone);
            $app->flash('email', $email);

            $error = '';

            if(trim($first_name) == false)
                $error .= "Please enter your first name.<br />";
            else if(preg_match("/^[a-zA-Z ]*$/", $first_name)===0)
                $error .= "First Name must be from letters only.<br />";
            if(trim($last_name) == false)
                $error .= "Please enter your last name.<br />";
            if(preg_match("/^[a-zA-Z ]*$/", $last_name)===0)
                $error .= "Last Name must be from letters only.<br />";
            if(trim($company) == false && preg_match("/^[a-zA-Z ]*$/", $company)===0){
                $error .= "Company must be from letters only.<br />";
            }
            if(trim($phone) == false && !preg_match("/^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i", $phone) ) {
                $error .= "Please enter a valid phone number.<br />";
            }
            if(trim($email) == false)
                $error .= "Please enter your email, you will be using this email to login.<br />";
            else if(preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)===0)
                $error .= "Please enter in a valid email.<br />";
            if(empty($password))
                $error .= "Please enter in a password.<br />";
            if( strlen($password) < 8 )
                $error .= "Password too short!<br />";
            if( !preg_match("#[0-9]+#", $password) )
                $error .= "Password must include at least one number!<br />";
            if( !preg_match("#[a-z]+#", $password) )
                $error .= "Password must include at least one letter!<br />";
            if( !preg_match("#[A-Z]+#", $password) )
                $error .= "Password must include at least one CAPS!<br />";
            //if( !preg_match("#\W+#", $password) ) {
            //	$error .= "Password must include at least one symbol!<br />";
            //}
            if(!empty($error)){
                $app->flash('error', $error);
                $app->redirect($app->request->getRootUri(). '/cms/createuser');
            }
            $auth = new \services\Authentication($email, $password);
            if(!$auth->createUser($first_name, $last_name, $company, $phone, $group)){
                $app->flash('error', $error . "<br />".$auth->getError());
                $app->redirect($app->request->getRootUri(). '/cms/createuser');
            }

            $_SESSION['user'] = $auth->getUser(); // $email;
            $app->redirect($app->request->getRootUri(). '/cms/');
        });

        $app->get("/forgotpassword", function () use ($app) {
            $flash = $app->view()->getData('flash');
            $error = "";
            echo "error: ".$flash['error'];
            if (isset($flash['error'])) {
                $error = $flash['error'];
            }
            $app->render("Authentication/Forgotpassword.php",array('error'=>$error));
        });
        $app->get("/forgotpassword/:activation_code", function($activation_code) use ($app){
            try {
                // query database for single article
                $activationCode = \ORM::for_table('users')->where( 'forgotten_password_code', $activation_code )->find_one();
                if ($activationCode != false  && $activationCode->forgotten_password_time+(20*60) > time()) {
                    // if found, then render password reset page
                    $app->render("Authentication/ForgotPasswordForm.php");
                } else {
                    // else throw exception
                    $flash = $app->view()->getData('flash');
                    $flash['error'] = "Activation code expired, please try the forgotton password link again";
                    $app->redirect($app->request->getRootUri(). '/cms/login');
                }
            } catch (\ResourceNotFoundException $e) {
                // return 404 server error
                $app->response()->status(404);
            } catch (\Exception $e) {
                $app->response()->status(400);
                $app->response()->header('X-Status-Reason', $e->getMessage());
            }
        });
        $app->post("/forgotpassword", function() use ($app){
            $email = $app->request()->post('email');
            if(trim($email) == false || preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/", $email)===0 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                $app->flash('error', "Please enter in a valid email.<br />");
                $app->redirect($app->request->getRootUri(). '/cms/forgotpassword');
            }
            //run the forgotten password method to email an activation code to the user

            $auth = new \services\Authentication($email);
            if ($auth->fogottenPassword()){
                //if there were no errors
                $app->redirect($app->request->getRootUri(). '/cms/success');
            }
            else{
                $app->flash('error', $auth->getError());
                $app->redirect($app->request->getRootUri(). '/cms/forgotpassword');
            }
        });

        $app->get("/success", function () use ($app) {
            $app->render("Authentication/Succes.php");
        });


        $app->get('(/)(:slug+)', $authenticate, function( $p=array() ) use ($app)
        {
            $users = \ORM::for_table('cms_user')
                    ->select('id')
                    ->select('username')
                    ->select('email')
                    ->select('first_name')
                    ->select('last_name')
                    ->select('phone')
                    ->select('company')
                    ->select('active')
                    ->select('role')
                    ->find_array();

            $modules = array(
                array( "id"=>1, "name"=>"Pages", "icon"=>"file-o", "path"=>"page" ),
                array( "id"=>2, "name"=>"Collections", "icon"=>"picture-o", "path"=>"collection"),
                array( "id"=>3, "name"=>"Users", "icon"=>"users", "path"=>"user", "data"=> $users),
                array( "id"=>4, "name"=>"Settings", "icon"=>"cog", "path"=>"setting", "data"=> (array) $app->config('theme') ),
                array( "id"=>5, "name"=>"Help", "icon"=>"question", "path"=>"help")
                //array( "id"=>4, "name"=>"Assets", "icon"=>"file-image-o", "path"=>"assets"),
            );

            $app->render( 'index.php', array(
                "base"=>$app->request->getRootUri(),
                "settings"=>json_encode( array_merge( array(
                    "api"=>$app->request->getRootUri().'/api/'.VERSION.'/',
                    "user"=>$_SESSION['user']['email'],
                    "root"=>$app->request->getRootUri().'/cms',
                    "home"=>'pages'
                ), (array) $app->config('theme') ) ),
                "modules"=> json_encode($modules)
            ));

        } );

    });