<?php
/**
 * Common configuration
 */

// Init application mode
$config['app']['mode'] = get_mode(
    array('localhost', '127.0.0.1')
);

// DB
$config['db'] = array(
    'prefix' => 'cms',
    'driver' => 'mysql',
    'dbpath' => 'localhost',
    'dbname' => 'default',
    'username' => 'root',
    'password' => ''
);

$config['db']['dsn'] = sprintf(
    '%s:dbname=%s;host:%s;',
    $config['db']['driver'],
    $config['db']['dbname'],
    $config['db']['dbpath']

);

//uploads
$config['upload'] = array(
    'folder' => 'public/data/upload/',
    "maxsize"   => 30000000,
    "allowed"   => array(
        'application/pdf',
        'image/jpeg',
        'image/jpg',
        'image/png'
    ),
    "hash"      =>true,
    "override"  =>true
);

// View
$config['app']['view'] = new Slim\Views\Twig();

// Cache TTL in seconds
$config['app']['cache.ttl'] = 60;

// Max requests per hour
$config['app']['rate.limit'] = 1000;

// Logger
$config['app']['log.writer'] = new \Flynsarmy\SlimMonolog\Log\MonologWriter(array(
    'handlers' => array(
        new \Monolog\Handler\StreamHandler(
            realpath(__ROOT__ . 'public/data/logs')
                .'/'. $config['app']['mode']  . '_' .date('Y-m-d').'.log'
        ),
    ),
));




function get_mode($whitelist)  {
    $isapi = (bool) preg_match('|/api/v.*$|', $_SERVER['REQUEST_URI']);
    return $isapi==1 ? 'api' : ( in_array($_SERVER['HTTP_HOST'], $whitelist) ? 'development' : 'production' );
}
