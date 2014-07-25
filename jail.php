<?php

function cr_jail_check() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    $t = character_meta_int( cr_meta_type_character, CR_CHARACTER_JAIL_TIME );

    if ( time() <= $t ) {
        $valid_actions = array( 'profile', 'dashboard' );

        if ( ! in_array( game_get_action(), $valid_actions ) ) {
            game_set_action( 'jail' );
        }
    }
}

add_action( 'action_set', 'cr_jail_check' );

function cr_zone_jail() {
    global $character;

?>
<div class="row">
  <div class="col-md-6">
    <h2>Jail</h2>
  </div>
  <div class="col-md-6 text-right">

  </div>
</div>
<div class="row">
<p class="lead">Home to the poor criminals who reached too far and
got caught.</p>
</div>
<?php

//CR_CHARACTER_JAIL_TIME
}

function cr_jail_locked_content() {
    global $character;

    if ( strcmp( 'jail', game_get_action() ) ) {
       return;
    }

    $time_left = character_meta_int(
        cr_meta_type_character, CR_CHARACTER_JAIL_TIME ) - time();
?>
<div class="row">
  <div class="col-xs-12">
    <h2>Jail</h2>
  </div>
</div>
<?php

    if ( $time_left > 0 ) {

?>
<div class="row">
  <h3>You're stuck in jail and locked up for another
    <?php echo( time_expand( $time_left ) ); ?>.</h3>
</div>
<?php

    } else {

?>
<div class="row">
  <p class="lead">You stare at the rows of people trapped behind bars.</p>
</div>
<?php

    }
}

add_action( 'do_page_content', 'cr_jail_locked_content' );
