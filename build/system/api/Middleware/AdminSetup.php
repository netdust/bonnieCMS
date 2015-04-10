<?php
namespace api\Middleware;

class AdminSetup extends \Slim\Middleware
{
    protected $isAdmin;

    public function __construct()
    {

    }

    public function call()
    {
        $this->isAdmin = (bool) preg_match('|/admin/?.*$|', $this->app->request->getPath());

        if ( $this->isAdmin ) {

            # middleware
            $this->app->push( $this,  new \Slim\Extras\Middleware\CsrfGuard( ) );

            $this->routes();
            $this->setupView();
            $this->setupLogin();
            $this->setupPasswordReset();

        }
        $this->next->call();
    }

    protected function setupView()
    {
        // Init Views
        $view = $this->app->view();
        try {

            $view->parserDirectory = __ROOT__.'vendor/Twig';
            $view->twigTemplateDirs = array(
                __ROOT__.'public/admin/tpl'.DS
            );
            $view->parserOptions = array(
                'debug' => false
            );

        } catch (\PDOException $e) {
            $this->app->getLog()->error($e->getMessage());
        }

    }

    protected function routes()
    {


        $this->app->get('/admin(/)(:slug+)', function( $p=array() )
        {
            /*
            $pages = \controller\PageController::getAll_as_array();
            $collections = \controller\CollectionController::getAll_as_array();

            $r = \Model::factory('Config')->find_many();
            $config = array();

            foreach( $r as $item ){
                $config[$item->key] = $item->value;
            }

            $modules = array(
                array( "id"=>1, "name"=>"Pages", "icon"=>"file-o", "path"=>"page" ),
                array( "id"=>2, "name"=>"Collections", "icon"=>"picture-o", "path"=>"collection"),
                //array( "id"=>4, "name"=>"Assets", "icon"=>"file-image-o", "path"=>"assets"),
            );

            if( $this->app->user->hasPermission('create_user') ) {
                $user = $this->app->user->session();
                $user['permissions'] = $this->app->user->getRoles();
                $modules[] = array( "id"=>3, "name"=>"Users", "icon"=>"users", "path"=>"user", "data"=> $user );
            }
            if( $this->app->user->hasPermission('create_settings') )
            {
                $modules[] = array( "id"=>4, "name"=>"Settings", "icon"=>"cog", "path"=>"setting" );
            }

            $modules[] = array( "id"=>5, "name"=>"Help", "icon"=>"question", "path"=>"help");

            $this->app->render( 'admin.html', array(
                "base"=>$this->app->request->getRootUri(),
                "settings"=>json_encode(array_merge( array(
                    "api"=>$this->app->request->getRootUri().'/api/'.VERSION.'/',
                    "user"=>$_SESSION['auth_user']['username'],
                    "root"=>$this->app->request->getRootUri().'/admin',
                    "home"=>'pages'
                ), $config )),
                "modules"=> json_encode($modules)
            ));

            */

        } );
    }

    protected function setupLogin()
    {
        $this->app->get('/admin/login',  function( )
        {
            $this->app->render( 'login.html', array('state'=>'login') );
        } );

        $this->app->post('/admin/login', function( )
        {
            $auth = new \Slipp\Helper\Authentication($cnf_auth);
            $req = $this->app->request();

            if(  !$auth->login( $req->post('user'), $req->post('pass'), $req->post('forgot') ) )
            {
                $this->app->flash( 'error', 'Authentication failed, please check username and password');
                $this->app->redirect( $this->app->request->getRootUri().'/admin/login' );
            }

            $this->app->redirect($this->app->request->getRootUri().'/admin');

        } );
    }
    protected function setupPasswordReset()
    {

        $this->app->get('/admin/forgot',  function( )
        {
            $this->app->render( 'login.html', array('state'=>'forgot') );
        } );

        $this->app->post('/admin/forgot',  function( )
        {
            require( __LIB__.'/phpmailer/PHPMailerAutoload.php');

            $req = $this->app->request();

            $mail = new PHPMailer();

            $mail->setFrom('cms@netdust.be', 'Netdust CMS');
            $mail->addReplyTo('no-reply@netdust.be', 'Netdust CMS');

            $model = \Model::factory('User')->where('email', $req->post('email'))->find_one();
            if ( $model ) {
                $mail->addAddress($req->post('email'), $model->realname);

                $mail->Subject = 'Netdust CMS: Password recovery';
                $mail->msgHTML( $this->email_template(__ROOT__.'public/admin/tpl/email.html', array('url'=> 'http://'.$this->app->request->getPath().'/recover?') ) );
                $mail->AltBody = '';

                if( $mail->send() )
                    echo json_encode( array('success'=>'user deleted') );
                else
                    echo json_encode( array('error'=>'mail not send') );
            } else {
                echo json_encode( array('error'=>'user does not exist') );
            }



        } );
    }

    private function email_template($file, $vars=array())
    {
        ob_start();
        extract($vars);
        include($file);
        return ob_get_contents() . (ob_end_clean() ? "" : "");
    }
}
