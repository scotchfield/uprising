<?php

require( GAME_CUSTOM_PATH . 'profile.php' );

require( GAME_CUSTOM_PATH . 'inventory.php' );
require( GAME_CUSTOM_PATH . 'map.php' );
require( GAME_CUSTOM_PATH . 'predicate.php' );
require( GAME_CUSTOM_PATH . 'select.php' );
require( GAME_CUSTOM_PATH . 'tutorial.php' );
require( GAME_CUSTOM_PATH . 'zone.php' );

$custom_start_page = 'title.php';

$custom_default_action = 'map';


define( 'cr_meta_type_character',    1 );
define( 'cr_meta_type_inventory',    2 );

define( 'CR_TUTORIAL_STATUS',     1 );
define( 'CR_CHARACTER_NAME',      2 );
define( 'CR_CHARACTER_MONEY',     3 );
define( 'CR_CHARACTER_TIP',       4 );
define( 'CR_CURRENT_ZONE',        5 );

function cr_login() {
    global $character;

    ensure_character_meta( $character[ 'id' ], cr_meta_type_character,
                           CR_TUTORIAL_STATUS );
    ensure_character_meta( $character[ 'id' ], cr_meta_type_character,
                           CR_CHARACTER_NAME );
    ensure_character_meta( $character[ 'id' ], cr_meta_type_character,
                           CR_CHARACTER_MONEY );
}

add_action( 'select_character', 'cr_login' );

function cr_header() {
    global $game, $user, $character;

    $obj = array( '<!DOCTYPE html>',
'<html lang="en">',
'  <head>',
'    <meta charset="utf-8">',
'    <meta http-equiv="X-UA-Compatible" content="IE=edge">',
'    <meta name="viewport" content="width=device-width, initial-scale=1">',
'    <title>' . GAME_NAME . ' (' . $game->get_action() . ')</title>',
'    <link rel="stylesheet" href="' . GAME_URL . 'style/bootstrap.min.css">',
'    <link rel="stylesheet" href="' . GAME_CUSTOM_STYLE_URL . 'cr.css">',
'    <link href="http://fonts.googleapis.com/css?' .
         'family=Raleway:400,500" rel="stylesheet" type="text/css">',
'  </head>',
'  <body>',
'    <div id="popup" class="invis"></div>',
'    <div class="navbar navbar-default navbar-fixed-top" role="navigation">',
'      <div class="container">',
'        <div class="navbar-header">',
'          <button type="button" class="navbar-toggle" data-toggle=' .
               '"collapse" data-target=".navbar-collapse">',
'            <span class="sr-only">Toggle navigation</span>',
'            <span class="icon-bar"></span>',
'            <span class="icon-bar"></span>',
'            <span class="icon-bar"></span>',
'          </button>',
'          <a class="navbar-brand" href="' . GAME_URL . '">' .
               GAME_NAME . '</a>',
'        </div>'
        );

    if ( FALSE != $character ) {
        array_push( $obj,
'        <div class="collapse navbar-collapse">',
'          <ul class="nav navbar-nav">',
'            <li class="dropdown">',
'              <a href="#" class="dropdown-toggle" data-toggle="dropdown">' .
                   'Navigate <b class="caret"></b></a>',
'              <ul class="dropdown-menu">',
'                <li class="dropdown-header">Main Locations</li>',
'                <li><a href="?action=zone&amp;zone_tag=cydonia">' .
                     'Cydonia Heavy Industries</a></li>',
'                <li><a href="?action=zone&amp;zone_tag=minstall">' .
                     'Mech Installations</a></li>',
'                <li><a href="?action=zone&amp;zone_tag=cityhall">' .
                     'City Hall</a></li>',
'                <li><a href="?action=zone&amp;zone_tag=wordtruth">' .
                     'The Word of Truth</a></li>',
'                <li class="divider"></li>',
'                <li class="dropdown-header">Combat Locations</li>',
'                <li><a href="?action=zone&amp;zone_tag=titanrift">' .
                     'Titan\'s Rift</a></li>',
'                <li><a href="?action=zone&amp;zone_tag=epsilon">' .
                     'The Epsilon Rift</a></li>',
'              </ul>',
'            </li>',
'            <li><a href="?action=about">About</a></li>',
'            <li><a href="?action=contact">Contact</a></li>',
'          </ul>',
'          <ul class="nav navbar-nav navbar-right">',
'            <li class="dropdown">',
'              <a href="#" class="dropdown-toggle" data-toggle="dropdown">' .
                   $character[ 'character_name' ] .
                   ' <b class="caret"></b></a>',
'              <ul class="dropdown-menu">',
'                <li><a href="?action=profile">Profile</a></li>',
'                <li><a href="?action=questlog">Quest Log</a></li>',
'                <li><a href="?action=mech">Active Mech</a></li>',
'                <li><a href="?action=inventory">Inventory</a></li>',
'                <li class="divider"></li>',
'                <li><a href="?action=dashboard">Dashboard</a></li>',
'                <li class="divider"></li>',
'                <li><a href="game-setting.php?setting=change_character">' .
                     'Change Character</a></li>',
'                <li><a href="game-logout.php">Log out</a></li>',
'              </ul>',
'            </li>',
'          </ul>',
'        </div><!--/.nav-collapse -->'
            );
    }

    array_push( $obj,
'      </div>',
'    </div>',
'',
'    <div class="container">'
        );

    echo join( "\n", $obj );
}

function cr_footer() {
    global $game, $character;

    $footer_text = '';

    echo '    </div>';
    echo '    <div id="footer"><div class="container">';
    echo '<p class="text-muted">' . $footer_text . '</p>';
    echo '    </div></div>';
    echo '<script src="' . GAME_URL . 'style/popup.js"></script>';
    echo '<script src="' . GAME_URL . 'style/jquery.min.js"></script>';
    echo '<script src="' . GAME_URL . 'style/bootstrap.min.js"></script>' .
         "\n";
    echo '</body></html>';
}

add_action( 'game_header', 'cr_header' );
add_action( 'game_footer', 'cr_footer' );



function cr_tip_print() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    $tip = character_meta( cr_meta_type_character, CR_CHARACTER_TIP );

    if ( 0 < strlen( $tip ) ) {
        echo( $tip );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, '' );
    }
}

add_action_priority( 'do_page_content', 'cr_tip_print' );

function cr_about() {
    global $game;

    if ( strcmp( 'about', $game->get_action() ) ) {
       return;
    }

    echo '<h1>BOB SAGET</h1>';
}

function cr_contact() {
    global $game;

    if ( strcmp( 'contact', $game->get_action() ) ) {
       return;
    }

    echo '<h1>OH BOB SAGET</h1>';
}

add_action( 'do_page_content', 'cr_about' );
add_action( 'do_page_content', 'cr_contact' );


function cr_item_popup_str( $item ) {
    return '<a href="#" onmouseover="popup(\'' .
           '<span class=&quot;item_name&quot;>' . $item[ 'name' ] .
           '</span>' .
           '<hr><span>' . $item[ 'description' ] . '</span>' .
           '\')" onmouseout="popout()" class="item">' . $item[ 'name' ] .
           '</a>';
}

function cr_item_string( $item ) {
    return '<a href="#" onmouseover="popup(\'' .
           '<span class=&quot;item_name&quot;>' . $item[ 'name' ] .
           '</span><hr><span>' . $item[ 'description' ] . '</span>' .
           '\')" onmouseout="popout()" class="item">' . $item[ 'name' ] .
           '</a>';
}

function cr_validate_user( $args ) {
    if ( ! isset( $args[ 'user_id' ] ) ) {
        return;
    }

    set_user_max_characters( $args[ 'user_id' ], 1 );
}

add_action( 'validate_user', 'cr_validate_user' );
