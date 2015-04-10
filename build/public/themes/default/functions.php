<?php



$app->hook('slim.before.router', function () use ($app)
{
    $req = $app->request();
   if( $req->getHost() == 'dreamperfectie.be' || $req->getHost() == 'www.dreamperfectie.be') {
        $_SESSION['i18n.localeId'] = 2;

   }
});

$app->post('/friendsform', function(  ) use ($app)
{
    $request = $app->request()->params();

    try {

        $persons = ORM::for_table('sms')->where('email', $request['email']);

        //if ($persons->count() > 0 ) {
        //    \Slipp\Helper\Util::echo_json( 200, '{"status":"error", "message": "user already in database"}' );
        //}
        //else {

            $person = ORM::for_table('sms')->create();
            $person->set_expr('modified', 'NOW()');
            $person->language = \Slipp\Helper\Util::get( $request, 'lg', 'fr' );
            $person->first_name = \Slipp\Helper\Util::get( $request, 'firstname', '' );
            $person->email = \Slipp\Helper\Util::get( $request, 'email', '' );
            $person->gsm = \Slipp\Helper\Util::get( $request, 'gsm', '' );
            $person->save();

            \Slipp\Helper\Util::echo_json( 200, '{"status":"success", "message": "user added to database", "id":'.$person->id.'}' );
        //}

    } catch (Exception $e)
    {
        \Slipp\Helper\Util::echo_json( 200, '{"status":"error", "message": "' . $e->getMessage()  . '"}' );
    }

    \Slipp\Helper\Util::echo_json( 200, '{"status":"error", "message": "nopes"}' );


});

$app->post('/form', function(  ) use ($app)
{
    $request = $app->request()->params();

    // 1 add friend to the database if he/she doesn't exist

    $friend = ORM::for_table('sms')->where('gsm', $request['friend_gsm']);

    if ($friend->count() == 0 ) {

        $friend = ORM::for_table('sms')->create();
        $friend->set_expr('modified', 'NOW()');
        $friend->language = \Slipp\Helper\Util::get( $request, 'friend_lg', 'fr' );
        $friend->first_name = \Slipp\Helper\Util::get( $request, 'friend_name', '' );
        $friend->email = \Slipp\Helper\Util::get( $request, 'friend_email', '' );
        $friend->gsm = \Slipp\Helper\Util::get( $request, 'friend_gsm', '' );
        $friend->save();

    }
    else {
        $friend = $friend->find_one();
        $friend->set_expr('modified', 'NOW()');
        $friend->save();
    }

    // 2 add user to the database if he/she doesn't exist

    $user = ORM::for_table('form')->where('email', $request['email']);

    if ($user->count() == 0 ) {
        $user = ORM::for_table('form')->create();
        $user->set_expr('modified', 'NOW()');
        $user->language = $app->config('i18n.locale');
        $user->gender = \Slipp\Helper\Util::get( $request, 'sex', 'f' );
        $user->last_name = \Slipp\Helper\Util::get( $request, 'lastname', '' ); //$request['lastname'];
        $user->first_name = \Slipp\Helper\Util::get( $request, 'firstname', '' ); //$request['firstname'];
        $user->street = \Slipp\Helper\Util::get( $request, 'street', '' ); //$request['street'];
        $user->nr = \Slipp\Helper\Util::get( $request, 'nr', '' ); //$request['nr'];
        $user->zip = \Slipp\Helper\Util::get( $request, 'code', '' ); //$request['code'];
        $user->city = \Slipp\Helper\Util::get( $request, 'city', '' ); //$request['city'];
        $user->country = \Slipp\Helper\Util::get( $request, 'country', 'B' ); //$request['country'];
        $user->email = \Slipp\Helper\Util::get( $request, 'email', '' ); //$request['email'];

        $nl = \Slipp\Helper\Util::get( $request, 'newsletterb', 0 );
        $user->newsletter_b = $nl==='on' ? 1 : 0;
        $nl = \Slipp\Helper\Util::get( $request, 'newsletterl', 0 );
        $user->newsletter_l = $nl==='on' ? 1 : 0;

        $user->save();
    }
    else {
        $user = $user->find_one();
    }


    // 3, check if user already added this friend before otherwise add them

    $inserts = ORM::for_table('form_sms')->where( array( 'form_id' => $user->id, 'sms_id' => $friend->id ) );
    if( $inserts->count == 0 ) {
        $formuser = ORM::for_table('form_sms')->create();
        $formuser->form_id = $user->id;
        $formuser->sms_id = $friend->id;
        $formuser->save();
    }

    \Slipp\Helper\Util::echo_json( 200, '{"status":"success", "message": "added to database"}' );

});

function get_locale() {
    global $app;
    $lcl = $app->config('i18n.locale')=='fr'?'nl':'fr';
    $url = $lcl == 'fr' ? 'miraclesleepingcreme.youragency.be/fr' : 'miraclesleepingcreme.youragency.be/nl';
    echo '<a data-bypass href="http://'.$url.'" class="language"><i>'.strtoupper($lcl).'</i></a>';
}

