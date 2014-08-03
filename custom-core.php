<?php

require( GAME_CUSTOM_PATH . 'profile.php' );

require( GAME_CUSTOM_PATH . 'career.php' );
require( GAME_CUSTOM_PATH . 'casino.php' );
require( GAME_CUSTOM_PATH . 'crime.php' );
require( GAME_CUSTOM_PATH . 'education.php' );
require( GAME_CUSTOM_PATH . 'fitness.php' );
require( GAME_CUSTOM_PATH . 'inventory.php' );
require( GAME_CUSTOM_PATH . 'jail.php' );
require( GAME_CUSTOM_PATH . 'map.php' );
require( GAME_CUSTOM_PATH . 'market.php' );
require( GAME_CUSTOM_PATH . 'predicate.php' );
require( GAME_CUSTOM_PATH . 'residence.php' );
require( GAME_CUSTOM_PATH . 'select.php' );
require( GAME_CUSTOM_PATH . 'skills.php' );
require( GAME_CUSTOM_PATH . 'status.php' );
require( GAME_CUSTOM_PATH . 'title.php' );
require( GAME_CUSTOM_PATH . 'tutorial.php' );
require( GAME_CUSTOM_PATH . 'zone.php' );

//$custom_start_page = 'title.php';

//$custom_default_action = 'status';


define( 'cr_meta_type_character',    1 );
define( 'cr_meta_type_inventory',    2 );
define( 'cr_meta_type_buff',         3 );

define( 'CR_TUTORIAL_STATUS',     1 );
define( 'CR_CHARACTER_NAME',      2 );
define( 'CR_CHARACTER_MONEY',     3 );
define( 'CR_CHARACTER_TIP',       4 );
define( 'CR_CURRENT_ZONE',        5 );

define( 'CR_CHARACTER_HEALTH',         50 );
define( 'CR_CHARACTER_HEALTH_MAX',     51 );

define( 'CR_CHARACTER_STAMINA',             60 );
define( 'CR_CHARACTER_STAMINA_TIMESTAMP',   61 );
define( 'CR_CHARACTER_STAMINA_MAX',         62 );

define( 'CR_CHARACTER_STR',       100 );
define( 'CR_CHARACTER_DEX',       101 );
define( 'CR_CHARACTER_INT',       102 );
define( 'CR_CHARACTER_CON',       103 );
define( 'CR_CHARACTER_APP',       104 );
define( 'CR_CHARACTER_POW',       105 );
define( 'CR_CHARACTER_EDU',       106 );
define( 'CR_CHARACTER_XP',        107 );

define( 'CR_CHARACTER_JOB_ID',         150 );
define( 'CR_CHARACTER_JOB_HIRED',      151 );
define( 'CR_CHARACTER_JOB_LASTPAID',   152 );

define( 'CR_CHARACTER_GYM_ID',         200 );

define( 'CR_CHARACTER_JAIL_TIME',      250 );

define( 'cr_game_meta_employers',   1 );
define( 'cr_game_meta_jobs',        2 );
define( 'cr_game_meta_crimes',      3 );
define( 'cr_game_meta_degrees',     4 );
define( 'cr_game_meta_courses',     5 );
define( 'cr_game_meta_gyms',        6 );


function cr_default_action() {
    global $user, $character;

    if ( FALSE == $user ) {
        game_set_action( 'title' );
    } else if ( FALSE == $character ) {
        game_set_action( 'select' );
    } else {
        game_set_action( 'map' );
    }
}

add_action( 'set_default_action', 'cr_default_action' );


function cr_login() {
    global $character;

    ensure_character_meta_keygroup(
        $character[ 'id' ], cr_meta_type_character, '',
        array(
            CR_CHARACTER_NAME, CR_CHARACTER_TIP, CR_CURRENT_ZONE
        ) );

    ensure_character_meta_keygroup(
        $character[ 'id' ], cr_meta_type_character, 0,
        array(
            CR_TUTORIAL_STATUS, CR_CHARACTER_MONEY,
            CR_CHARACTER_STAMINA_TIMESTAMP,
            CR_CHARACTER_XP,
            CR_CHARACTER_JOB_ID, CR_CHARACTER_JOB_HIRED,
            CR_CHARACTER_JOB_LASTPAID,
            CR_CHARACTER_JAIL_TIME
        ) );

    ensure_character_meta_keygroup(
        $character[ 'id' ], cr_meta_type_character, 1,
        array(
            CR_CHARACTER_GYM_ID
        ) );

    ensure_character_meta_keygroup(
        $character[ 'id' ], cr_meta_type_character, 10,
        array(
            CR_CHARACTER_STR, CR_CHARACTER_DEX, CR_CHARACTER_INT,
            CR_CHARACTER_CON, CR_CHARACTER_APP, CR_CHARACTER_POW,
            CR_CHARACTER_EDU
        ) );

    ensure_character_meta_keygroup(
        $character[ 'id' ], cr_meta_type_character, 100,
        array(
            CR_CHARACTER_HEALTH, CR_CHARACTER_HEALTH_MAX,
            CR_CHARACTER_STAMINA, CR_CHARACTER_STAMINA_MAX
        ) );

    cr_award_salary();
}

add_action( 'select_character', 'cr_login' );

function cr_header() {
    global $user, $character;

    if ( ! strcmp( 'title', game_get_action() ) ) {
        return;
    }

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo( GAME_NAME ); ?> (<?php echo( game_get_action() );
        ?>)</title>
    <link rel="stylesheet" href="<?php echo( GAME_URL );
        ?>style/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo( GAME_CUSTOM_STYLE_URL );
        ?>elysian.css">
    <link href="http://fonts.googleapis.com/css?family=Raleway:400,500"
          rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Oswald:700'
          rel='stylesheet' type='text/css'>
  </head>
  <body>
    <div id="popup" class="invis"></div>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle"
                  data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo( GAME_URL ); ?>"><?php
              echo( GAME_NAME ); ?></a>
        </div>
<?php

    if ( FALSE != $character ) {
?>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="?action=about">About</a></li>
            <li><a href="?action=contact">Contact</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php
                  echo( $character[ 'character_name' ] );
                  ?><b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="?action=profile">Profile</a></li>
                <li><a href="?action=inventory">Inventory</a></li>
                <li class="divider"></li>
                <li><a href="?action=dashboard">Dashboard</a></li>
                <li class="divider"></li>
                <li><a href="game-setting.php?setting=change_character">
                    Change Character</a></li>
                <li><a href="game-logout.php">Log out</a></li>
              </ul>
            </li>
          </ul>
        </div>
<?php
    }
?>
      </div>
    </div>

    <div class="container">
      <div class="row">

<?php
    if ( FALSE != $character ) {
        $sidebar_obj = array(
            '-' => '-',
            'profile' => 'Profile',
            'residence' => 'Residence',
            'inventory' => 'Inventory',
            '--' => '-',
            'career' => 'Career',
            'casino' => 'Casino',
            'crime' => 'Crime',
            'education' => 'Education',
            'fitness' => 'Fitness',
            'jail' => 'Jail',
            'market' => 'Market',
            'skills' => 'Skills'
        );
?>
        <div class="col-sm-2 col-md-2 sidebar">
          <div><b>Health</b><br>
          <?php echo( character_meta( cr_meta_type_character,
                          CR_CHARACTER_HEALTH ) . ' / ' .
                          character_meta( cr_meta_type_character,
                          CR_CHARACTER_HEALTH_MAX ) ); ?></div>
        <div><b>Stamina</b><br>
          <?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_STAMINA ), $precision = 2 ) . ' / ' .
                             character_meta_int( cr_meta_type_character,
                          CR_CHARACTER_STAMINA_MAX ) ); ?></div>
        <div><b>Money</b><br>
          $<?php echo( character_meta( cr_meta_type_character,
                          CR_CHARACTER_MONEY ) ); ?></div>

          <ul class="nav nav-sidebar">
<?php
        foreach ( $sidebar_obj as $k => $v ) {
            if ( ! strcmp( game_get_action(), $k ) ) {
                echo( '<li class="active">' .
                      '<a href="?action=' . $k . '">' . $v . '</a></li>' );
            } else if ( ! strcmp( '-', $v ) ) {
                echo( '</ul><ul class="nav nav-sidebar">' );
            } else {
                echo( '<li>' .
                      '<a href="?action=' . $k . '">' .     $v . '</a></li>' );
            }
        }
?>
          </ul>
        </div>
        <div class="col-sm-10 col-sm-offset-2 main">
<?php
    }
}

function cr_footer() {
    global $character;

    if ( ! strcmp( 'title', game_get_action() ) ) {
        return;
    }

    if ( FALSE != $character ) {
        echo "      </div>\n";
    }
?>
    </div>
  </div>
  <script src="<?php echo( GAME_URL ); ?>style/popup.js"></script>
  <script src="<?php echo( GAME_URL ); ?>style/jquery.min.js"></script>
  <script src="<?php echo( GAME_URL ); ?>style/bootstrap.min.js"></script>
  </body>
</html>
<?php
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
        echo( '<p class="tip">' . $tip . '</p>' );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, '' );
    }
}

add_action_priority( 'do_page_content', 'cr_tip_print' );

function cr_about() {
    if ( strcmp( 'about', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">About</h1>
</div>
<?php

    echo '<h1>BOB SAGET</h1>';
}

function cr_contact() {
    if ( strcmp( 'contact', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Contact</h1>
</div>
<?php

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



function cr_regen_stamina() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    $stamina = character_meta_float(
        cr_meta_type_character, CR_CHARACTER_STAMINA );

    // todo: support higher max stamina as meta later
    if ( $stamina < 100 ) {
        $stamina_seconds = time() - character_meta_int(
            cr_meta_type_character, CR_CHARACTER_STAMINA_TIMESTAMP );
        $stamina_gain = $stamina_seconds / 120.0;

        $new_stamina = min( 100, $stamina + $stamina_gain );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_STAMINA, $new_stamina );
    }

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_STAMINA_TIMESTAMP, time() );
}

add_action( 'character_load', 'cr_regen_stamina' );

function cr_add_buff( $buff_id, $duration ) {
    global $character;

    if ( ! cr_has_buff( $buff_id ) ) {
        $buff_time = time() + $duration;
    } else {
        $buff_time = intval( character_meta( cr_meta_type_buff, $buff_id ) ) +
            $duration;
    }

    ensure_character_meta( $character[ 'id' ], cr_meta_type_buff, $buff_id );
    update_character_meta( $character[ 'id' ], cr_meta_type_buff, $buff_id,
        $duration );
}

function cr_has_buff( $buff_id ) {
    $buff = character_meta( cr_meta_type_buff, $buff_id );

    if ( FALSE == $buff ) {
        return FALSE;
    } else if ( intval( $buff ) <= time() ) {
        return FALSE;
    }

    return TRUE;
}

