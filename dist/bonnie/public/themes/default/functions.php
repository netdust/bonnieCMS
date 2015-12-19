<?php

$app->post('/forms', function(  ) use ($app)
{
    $request = (array) $app->request()->params();
    var_dump( $request );
});

function handle_args( $arg, $cb ) {
    $id = isset( $arg['id'] ) ? $arg['id']  : '';
    $label = isset( $arg['label'] ) ? $arg['label']  : '';
    $value = isset( $arg['value'] ) ? $arg['value']  : '';
    $required = isset( $arg['required'] ) ? $arg['required']  : false;

    if( isset( $arg['label'] ) ) unset( $arg['label'] );

    return $cb( $id, $label, $value, $required, $arg );
};

shortcode( 'submit', function($arg)  {
    $arg['type'] = 'submit';
    $arg['class'] = 'button ' . ( isset( $arg['class'] ) ? $arg['class']  : '' );
    return \helpers\html::input( '', $arg );
});
shortcode( 'label', function($arg)  {
    return handle_args($arg, function($id, $label, $value, $required, $arg) {
        return '<label for="'.$id.'">'.$label. ( $required ? " *":"" ).'</label>';
    } );
});
shortcode( 'text', function($arg)  {
    $arg['type'] = isset( $arg['type'] ) ? $arg['type']  : 'text';
    return handle_args($arg, function($id, $label, $value, $required, $arg) {
        $el ='';
        $el .= '<p class="ui">';
        $el .= '    <label for="'.$id.'">'.$label. ( $required ? " *":"" ).'</label>';
        $el .= \helpers\html::input( $value, $arg );
        $el .= '</p>';
        return $el;
    } );
});
shortcode( 'select', function($arg)  {
    $options = explode( ',', $arg['options'] );
    array_walk($options, function (&$v, $k) { $v = '<option>'.$v.'</option>'; });
    return handle_args($arg, function($id, $label, $value, $required, $arg) use ( $options ) {
        $el ='';
        $el .= '<p class="ui">';
        $el .= '    <label for="'.$id.'">'.$label. ( $required ? " *":"" ).'</label>';
        $el .= '    <select name="'.$id.'" id="'.$id.'" required="'.$required.'">'. implode( $options ).'</select>';
        $el .= '</p>';
        return $el;
    } );
});
shortcode( 'radio', function($arg)  {
    $arg['type'] = 'radio';
    return handle_args($arg, function($id, $label, $value, $required, $arg) {
        $el ='';
        $el .= '<p class="ui radio">';
        $el .= \helpers\html::input( $value, $arg );
        $el .= '    <label for="'.$id.'">'.$label.'</label>';
        $el .= '</p>';
        return $el;
    } );
});
shortcode( 'checkbox', function($arg)  {
    $arg['type'] = 'checkbox';
    return handle_args($arg, function($id, $label, $value, $required, $arg) {
        $el ='';
        $el .= '<p class="ui checkbox">';
        $el .= \helpers\html::input( $value, $arg );
        $el .= '    <label for="'.$id.'">'.$label.'</label>';
        $el .= '</p>';
        return $el;
    } );
});

$app->hook('admin.before.render', function ( $args ) use ($app) {

    /*
    unset( $args->modules[1] );
    unset( $args->modules[2] );
    array_splice( $args->modules, 1, 0, (array) array( array( "name"=>"Players", "icon"=>"trophy", "path"=>"player", 'require'=>'../../../public/themes/default/players/module.js') ) );
    //array_splice( $args->modules, 2, 0, (array) array( array( "name"=>"Media", "icon"=>"image", "path"=>"media", 'require'=>'../../../public/themes/default/media/module.js') ) );
    array_walk($args->modules, function (&$item, $key)
    {
        $item['id']=$key;
    });
*/
});

$app->get('/api/v1/media', function(  ) use ($app)
{
    $players = \ORM::for_table('form')->find_array();
    echo json_encode( $players );
});
$app->put('/api/v1/media/:id', function( $id  ) use ($app)
{
    $player = \ORM::for_table('form')->find_one($id);

    if( $player ) {
        $request = (array) json_decode($app->request()->getBody());
        foreach( $request as $r => $value ) {
            $player->{$r} = $value;
        }
        $player->save();
        echo json_encode( $player->as_array() );
    }
});

$app->get('/api/v1/players', function(  ) use ($app)
{
    $players = \ORM::for_table('form')->find_array();
    echo json_encode( $players );
});
$app->put('/api/v1/player/:id', function( $id  ) use ($app)
{
    $player = \ORM::for_table('form')->find_one($id);

    if( $player ) {
        $request = (array) json_decode($app->request()->getBody());
        foreach( $request as $r => $value ) {
            $player->{$r} = $value;
        }
        $player->save();
        echo json_encode( $player->as_array() );
    }
});