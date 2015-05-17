<?php

// autoload_namespaces.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname(dirname(dirname($vendorDir)));

$baseDir = $baseDir.'\build';

return array(
    'plugins' => array($baseDir . '/public',),
    'libraries' => array($baseDir . '/system/api'),
    'helpers' => array($baseDir . '/system/api'),
    'cms' => array($baseDir . '/system/api/Model'),
    'api' => array($baseDir . '/system'),
    'Twig_Extensions_' => array($vendorDir . '/slim/extras/Views/Extension'),
    'Twig_' => array($vendorDir . '/twig/twig/lib'),
    'Slim\\Extras' => array($vendorDir . '/slim/extras'),
    'Slim' => array($vendorDir . '/slim/middleware/src', $vendorDir . '/slim/slim'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log'),
    'Michelf' => array($vendorDir . '/michelf/php-markdown'),
    'Flynsarmy\\SlimMonolog' => array($vendorDir . '/flynsarmy/slim-monolog'),
    'Aptoma' => array($vendorDir . '/aptoma/twig-markdown/src'),
);
