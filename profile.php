<?php

function cr_profile_content() {
    global $character;

    if ( strcmp( 'profile', game_get_action() ) ) {
       return;
    }

    ensure_character_achievements();

    $job = cr_get_job( $character[ 'meta' ][ cr_meta_type_character ][
                       CR_CHARACTER_JOB_ID ] );
    if ( FALSE != $job ) {
        $employer = get_game_meta(
            cr_game_meta_employers, $job[ 'meta_value' ][ 'employer' ] );
    }

?><div class="row">
  <div class="col-md-6">
    <h2>Profile</h2>

    <dl class="dl-horizontal">
      <dt>Name</dt>
      <dd><?php echo( $character[ 'character_name' ] ); ?>&nbsp;</dd>
      <dt>Health</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_HEALTH ] . ' / ' .
                      $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_HEALTH_MAX ] ); ?></dd>
      <dt>Stamina</dt>
      <dd><?php echo( round( floatval(
                          $character[ 'meta' ][ cr_meta_type_character ][
                          CR_CHARACTER_STAMINA ] ), $precision = 2 ) . ' / ' .
                      intval( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_STAMINA_MAX ] ) ); ?></dd>
      <dt>Money</dt>
      <dd>$<?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                       CR_CHARACTER_MONEY ] ); ?>&nbsp;</dd>
      <dt>Career</dt>
      <dd><?php
    if ( FALSE != $job ) {
        echo( $job[ 'meta_value' ][ 'title' ] . ' at ' .
              $employer[ 'meta_value' ] );
    } else {
        echo( '<a href="?action=zone&zone_tag=career">Unemployed</a>' );
    }?></dd><?php
    if ( $character[ 'id' ] == 3 ) {
        echo( '<dt>XP Points:</dt><dd>' .
              $character[ 'meta' ][ cr_meta_type_character ][
                  CR_CHARACTER_XP ] . '</dd>' );
    }?>
    </dl>

  </div>
  <div class="col-md-6">

    <h2>Stats</h2>

    <dl class="dl-horizontal">
      <dt>Strength</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_STR ] ); ?></dd>
      <dt>Dexterity</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_DEX ] ); ?></dd>
      <dt>Intelligence</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_INT ] ); ?></dd>
      <dt>Constitution</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_CON ] ); ?></dd>
      <dt>Appearance</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_APP ] ); ?></dd>
      <dt>Mental</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_POW ] ); ?></dd>
      <dt>Education</dt>
      <dd><?php echo( $character[ 'meta' ][ cr_meta_type_character ][
                      CR_CHARACTER_EDU ] ); ?></dd>


    </dl>

  </div>
</div>
<div class="row">
  <div class="col-md-6">

    <h2>Achievements</h2>
<?php
    if ( 0 == count( $character[ 'achievements' ] ) ) {
        echo( '<h4>None yet!</h4>' );
    } else {
        echo( '<dl class="dl-horizontal">' );
        foreach ( $character[ 'achievements' ] as $achieve ) {
            echo( '<dt>' . $achieve[ 'achieve_title' ] . '</dt><dd>' .
                  $achieve[ 'achieve_text' ] . '</dd><dd>' .
                  date( 'F j, Y, g:ia', $achieve[ 'timestamp' ] ) .
                  '</dd>' );
        }
        echo( '</dl>' );
    }
?>
  </div>
  <div class="col-md-6">

  </div>
</div><?php
}

add_action( 'do_page_content', 'cr_profile_content' );
