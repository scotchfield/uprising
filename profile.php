<?php

function cr_profile_content() {
    global $character;

    if ( strcmp( 'profile', game_get_action() ) ) {
       return;
    }

    $job = cr_get_job( character_meta( cr_meta_type_character,
                       CR_CHARACTER_JOB_ID ) );
    if ( FALSE != $job ) {
        $employer = get_game_meta(
            cr_game_meta_employers, $job[ 'meta_value' ][ 'employer' ] );
    }

?>
<div class="row text-right">
  <h1 class="page_section">Profile</h1>
</div>
<div class="row">
  <div class="col-md-6">
    <h2>Details</h2>

    <dl class="dl-horizontal">
      <dt>Name</dt>
      <dd><?php echo( $character[ 'character_name' ] ); ?>&nbsp;</dd>
      <dt>Health</dt>
      <dd><?php echo( character_meta( cr_meta_type_character,
                      CR_CHARACTER_HEALTH ) . ' / ' .
                      character_meta( cr_meta_type_character,
                      CR_CHARACTER_HEALTH_MAX ) ); ?></dd>
      <dt>Stamina</dt>
      <dd><?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_STAMINA ), $precision = 2 ) . ' / ' .
                      character_meta_int( cr_meta_type_character,
                          CR_CHARACTER_STAMINA_MAX ) ); ?></dd>
      <dt>Money</dt>
      <dd>$<?php echo( character_meta( cr_meta_type_character,
                       CR_CHARACTER_MONEY ) ); ?>&nbsp;</dd>
      <dt>Career</dt>
      <dd><?php
    if ( FALSE != $job ) {
        echo( $job[ 'meta_value' ][ 'title' ] . ' at ' .
              $employer[ 'meta_value' ] );
    } else {
        echo( '<a href="?action=career">Unemployed</a>' );
    }?></dd><?php
    if ( $character[ 'id' ] == 3 ) {
        echo( '<dt>XP Points:</dt><dd>' .
              round( character_meta_float( cr_meta_type_character,
                  CR_CHARACTER_XP ), $precision = 2 ) . '</dd>' );
    }?>
      <dt>Level</dt>
      <dd><?php echo( cr_get_level( character_meta_float(
          cr_meta_type_character, CR_CHARACTER_XP ) ) ); ?></dd>
    </dl>

  </div>
  <div class="col-md-6">

    <h2>Stats</h2>

    <dl class="dl-horizontal">
      <dt>Strength</dt>
      <dd><?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_STR ), $precision = 2 ) ); ?></dd>
      <dt>Dexterity</dt>
      <dd><?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_DEX ), $precision = 2 ) ); ?></dd>
      <dt>Intelligence</dt>
      <dd><?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_INT ), $precision = 2 ) ); ?></dd>
      <dt>Constitution</dt>
      <dd><?php echo( round( character_meta_float( cr_meta_type_character,
                          CR_CHARACTER_CON ), $precision = 2 ) ); ?></dd>
      <dt>Appearance</dt>
      <dd><?php echo( character_meta( cr_meta_type_character,
                      CR_CHARACTER_APP ) ); ?></dd>
      <dt>Mental</dt>
      <dd><?php echo( character_meta( cr_meta_type_character,
                      CR_CHARACTER_POW ) ); ?></dd>
      <dt>Education</dt>
      <dd><?php echo( character_meta( cr_meta_type_character,
                      CR_CHARACTER_EDU ) ); ?></dd>


    </dl>

  </div>
</div>
<div class="row">
  <div class="col-md-6">

    <h2>Achievements</h2>
<?php
    if ( ( ! isset( $character[ 'meta' ][ game_meta_type_achievement ] ) ) ||
         ( 0 == count(
                    $character[ 'meta' ][ game_meta_type_achievement ] ) ) ) {
        echo( '<h4>None yet!</h4>' );
    } else {
        echo( '<dl class="dl-horizontal">' );
        $achieve_obj = get_achievements( $character[ 'id' ] );
        foreach ( $achieve_obj as $achieve ) {
            $meta = json_decode( $achieve[ 'meta_value' ], TRUE );
            echo( '<dt>' . $meta[ 'title' ] . '</dt><dd>' .
                  $meta[ 'text' ] . '</dd><dd>' .
                  date( 'F j, Y, g:ia', $achieve[ 'timestamp' ] ) .
                  '</dd>' );
        }
        echo( '</dl>' );
    }
?>
  </div>
  <div class="col-md-6">
<?php
    if ( 9 == $_SESSION[ 'u' ] ) {
        echo( '<h2>Dev Stuff</h2>' );
        echo( '<a href="game-setting.php?setting=clear_character_meta">Clear' .
              ' character meta</a>' );
    }
?>
  </div>
</div><?php
}

add_action( 'do_page_content', 'cr_profile_content' );


function cr_get_level( $xp ) {
    $xp_obj = array(
        1 => 5,
        2 => 10,
        3 => 15,
        4 => 20,
        5 => 30,
        6 => 40,
        7 => 50,
        8 => 70,
        9 => 100,
        10 => 150,
        11 => 200,
        12 => 300,
        13 => 400,
        14 => 500,
        15 => 750,
        16 => 1000,
        17 => 1500,
        18 => 2000,
        19 => 2500,
        20 => 3000,
    );

    foreach ( $xp_obj as $k => $v ) {
        if ( $xp < $v ) {
            return $k;
        }
    }

    return 0;
}

function cr_clear_character_meta( $args ) {
    global $character;

    if ( 9 != $_SESSION[ 'u' ] ) {
        return;
    }

    if ( FALSE == $character ) {
        return;
    }

    clear_all_character_meta( $character[ 'id' ] );

    unset( $_SESSION[ 'c' ] );
}

$custom_setting_map[ 'clear_character_meta' ] = 'cr_clear_character_meta';


function cr_achievement_print( $args ) {
    global $character;

    if ( ! isset( $args[ 'achievement_id' ] ) ) {
        return;
    }

    $achievement = get_achievement( $args[ 'achievement_id' ] );
    $meta = json_decode( $achievement[ 'meta_value' ], TRUE );

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_TIP, 'ACHIEVEMENT AWARDED<br>' .
        $meta[ 'title' ] . '<br>' . $meta[ 'text' ] );
}

add_action( 'award_achievement', 'cr_achievement_print' );

