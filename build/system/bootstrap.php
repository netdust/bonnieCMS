<?php

define('DS', DIRECTORY_SEPARATOR);
define('ENV', getenv('APP_ENV'));
define('VERSION', 'v1');
define('EXT', '.php');

define('__ROOT__', dirname( dirname(__FILE__) ) . DS );

@session_start();

// Autoloader
require_once __ROOT__ . 'system/vendor/autoload.php';

// Config
require_once __ROOT__. 'system/config.php';

// Models
require_all(  __ROOT__. 'system/api/Model' );
require_all(  __ROOT__. 'system/services' );
require_all(  __ROOT__. 'system/helpers' );

# add Configuration

// Create Application
$app = new \api\App($config['app']);


// Only invoked if mode is "development"
$app->configureMode('api', function () use ($app) {
    $app->config(array(
        'view'=> new api\View\JsonApiView(),
        'log.enable' => true,
        'log.level' => \Slim\Log::DEBUG,
        'debug' => false
    ));
});

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'log.level' => \Slim\Log::WARN,
        'debug' => false
    ));
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => true,
        'log.level' => \Slim\Log::DEBUG,
        'debug' => true
    ));
});

// Init database
try {
    if (!empty($config['db']['username'])) {
        \Model::$auto_prefix_models = '\\'.$config['db']['prefix'].'\\';
        \ORM::configure($config['db']['dsn']);
        \ORM::configure('username', $config['db']['username']);
        \ORM::configure('password', $config['db']['password']);
        ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    }
} catch (\PDOException $e) {
    $app->getLog()->error($e->getMessage());
}

// load configuration
try {
    $app->config($config);
    $themeconfigcontroller = new \api\Controller\ConfigController();
    $themeconfig = (array) $app->config('theme');
} catch (\PDOException $e) {
    $app->getLog()->error($e->getMessage());
}

// Plugins
try {
    $controller = new \api\Controller\PluginController();
} catch (\PDOException $e) {
    $app->getLog()->error($e->getMessage());
}

// setup frontend template engine
$view = $app->view();
$view->parserDirectory = __ROOT__.'system/vendor/Twig';
$view->twigTemplateDirs = array(
    __ROOT__ .'admin/tpl'.DS,
    __ROOT__ .'public/themes/'.$themeconfig['theme'].DS
);
$view->parserOptions = array(
    'debug' => true,
    'cache' => __ROOT__.'public/data/cache'
);
$view->parserExtensions = array(
    new \api\Extensions\ApiHelpers(),
    new \api\Extensions\ShareHelpers(),
    new \Aptoma\Twig\Extension\MarkdownExtension( new \Aptoma\Twig\Extension\MarkdownEngine\MichelfMarkdownEngine() ),
);




// Init themes and function
try {
    if(is_readable($path=__ROOT__.'public/themes/'.$themeconfig['theme'].DS.'functions.php')) {
        require $path;
    }
} catch (\PDOException $e) {
    $app->getLog()->error($e->getMessage());
}


function require_all($mod) {
    $fi = new FilesystemIterator($mod, FilesystemIterator::SKIP_DOTS);
    foreach($fi as $file) {
        if($file->isFile() and $file->isReadable() and '.' . $file->getExtension() == EXT) {
            require $file->getPathname();
        }
    }
}

class ResourceNotFoundException extends \RuntimeException{}

class FileUploadException extends \Exception{}
