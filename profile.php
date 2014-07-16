<?php

function cr_profile_content() {
    global $character;

    if ( strcmp( 'profile', game_get_action() ) ) {
       return;
    }

    ensure_character_achievements();

?><div class="row">
  <div class="col-md-6">
    <h3>Character</h3>

    <dl class="dl-horizontal">
      <dt>Name</dt>
      <dd><?php echo character_meta(
          cr_meta_type_character, CR_CHARACTER_NAME ); ?>&nbsp;</dd>
      <dt>Money</dt>
      <dd><?php echo character_meta(
          cr_meta_type_character, CR_CHARACTER_MONEY ); ?>&nbsp;</dd>
      <dt>Stamina</dt>
      <dd><?php echo intval( character_meta(
          cr_meta_type_character, CR_CHARACTER_STAMINA ) ); ?> / 100</dd>
      <dt>Career</dt>
      <dd><?php echo character_meta(
          cr_meta_type_character, CR_CHARACTER_JOB_ID ); ?></dd>
    </dl>

    <h3>Achievements</h3>
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
