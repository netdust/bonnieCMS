<?php
/**
 * Common configuration
 */

// Init application mode
$config['app']['mode'] = get_mode(
    array('localhost', '127.0.0.1')
);

// cookies
$config['app']['cookies.encrypt'] = true;
$config['app']['cookies.secret_key'] = 'BG?zm@A1t~6;|{euS,cA[)#gK~,<$p@0P$T~~I$,QRw&(5 BT4<,g w9}!3-}+/F';
$config['app']['cookies.cipher'] = MCRYPT_RIJNDAEL_256;
$config['app']['cookies.cipher_mode'] = MCRYPT_MODE_CBC;


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
    'folder' => 'public/data/uploads/',
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
$config['app']['log.writer'] = new \Slim\Extras\Log\DateTimeFileWriter(array(
    'path'      => __ROOT__ . 'public/data/logs',
    'name_format' => 'Y-m-d',
    'message_format' => '%label% - %date% - %message%'
));

function get_mode($whitelist)  {
    $isapi = (bool) preg_match('|/api/v.*$|', $_SERVER['REQUEST_URI']);
    return $isapi==1 ? 'api' : ( in_array($_SERVER['HTTP_HOST'], $whitelist) ? 'development' : 'production' );
}
