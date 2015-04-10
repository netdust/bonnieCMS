<?php
namespace api\Middleware;

class FrontendSetup extends \Slim\Middleware
{
    public function call()
    {
        $isAdmin = (bool) preg_match('|/admin/.*$|', $this->app->request->getPath());

        if ( !$isAdmin && !$this->app->isAPI() ) {

            // Init Views
            $view = $this->app->view();
            try {

                $view->parserDirectory = __ROOT__.'vendor/Twig';
                $view->twigTemplateDirs = array(
                    __ROOT__.'public/'.$this->app->config('theme').'/tpl'.DS
                );
                $view->parserOptions = array(
                    'debug' => true,
                    'cache' => __ROOT__.'share/cache'
                );

                # add Extensions
                $view->parserExtensions = array(
                    //new \Slipp\View\Extensions\Twig_Extension_ApiHelpers(),
                    //new \Slipp\View\Extensions\Twig_Extension_ShareHelpers(),
                    new \Aptoma\Twig\Extension\MarkdownExtension( new \Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine() ),
                );

            } catch (\PDOException $e) {
                $this->app->getLog()->error($e->getMessage());
            }

            if(is_readable($path= __ROOT__.'public/'.$this->app->config('theme').'/tpl'.DS.'functions.php')) {
                require $path;
            }

        }
        $this->next->call();
    }
}
