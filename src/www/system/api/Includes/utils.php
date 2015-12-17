<?php

function shortcode($h, $c) {
    \helpers\Shortcode::get('default')->register($h, $c);
}


function hook($h) {
    $app = \Slim\Slim::getInstance();
    $app->applyHook($h);
}

function config($c) {
    $app = \Slim\Slim::getInstance();
    echo $app->config($c);
}

function i18n($key=null) {

}


function email_template($file, $vars) {
    ob_start();
    extract($vars);
    include($file);
    return ob_get_contents() . (ob_end_clean() ? "" : "");
}

function send_mail( $from, $email, $tpl, $data=array() ) {

    try {

        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        // Set PHPMailer to use the sendmail transport
        //$mail->isSendmail();
        //Set who the message is to be sent from
        $mail->setFrom($from, $from);
        //Set an alternative reply-to address
        $mail->addReplyTo($from, $from);
        //Set who the message is to be sent to
        $mail->addAddress( $email, '' );
        //$mail->addAddress( 'stefan@netdust.be', '');
        //Set the subject line
        $mail->Subject = ( isset($data['emailsubject']) ? $data['emailsubject'] : 'email from '. $from );
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $mail->msgHTML( template_file($tpl, $data) );
        //Replace the plain text body with one created manually
        $mail->AltBody = '';

        $mail->CharSet = 'utf-8';

        if( $mail->send() ) {

        }

    }catch( Exception $e ){
        print_r( $e );
    }
}

function url() {
    $app = \Slim\Slim::getInstance();
    return  $app->request->getUrl();
}

function host() {
    $app = \Slim\Slim::getInstance();
    return  $app->request->getHost();
}

function root() {
    $app = \Slim\Slim::getInstance();
    return  $app->request->getRootUri();
}

function resource() {
    $app = \Slim\Slim::getInstance();
    return  $app->request->getResourceUri();
}

function path() {
    $app = \Slim\Slim::getInstance();
    return  $app->request->getPath();
}