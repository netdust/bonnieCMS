<?php

/**
	 * Get a boolean to determine if an account should be locked out due to
	 * exceeded login attempts within a given period
	 *
	 * @return	redirect and flash if locked out
*/


/*
	Hook for authentication
*/
$app->hook('slim.before.dispatch', function() use ($app) { 
   $user = null;
   if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
   }
   $app->view()->setData('user', $user);
});

$app->get("/logout", function () use ($app) {
	unset($_SESSION['user']);
	$app->view()->setData('user', null);
	$app->flash('error', 'Logout successful');
	$app->redirect($app->request->getRootUri(). '/admin/login');
});

