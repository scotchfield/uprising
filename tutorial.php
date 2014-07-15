<?php

define( 'TUTORIAL_STATUS_BIT_COMPLETE', 0 );

function cr_tutorial_check() {
    global $character;

    if ( FALSE == $character ) {
        return;
    }

    $t = character_meta( cr_meta_type_character, CR_TUTORIAL_STATUS );
    if ( ! get_bit( $t, TUTORIAL_STATUS_BIT_COMPLETE ) ) {
        game_set_action( 'tutorial' );
    }
}

add_action( 'action_set', 'cr_tutorial_check' );

function cr_tutorial_print() {
    global $character;
    if ( ! strcmp( 'tutorial', game_get_action() ) ) {
        $t = character_meta( cr_meta_type_character, CR_TUTORIAL_STATUS );

        if ( ! get_bit( $t, 1 ) ) {
?>
<h2>Welcome to X, the Shattered City.</h2>
<p class="lead">Some flavour text here.</p>
<p>Some more flavour text here.</p>
<p><a href="game-setting.php?setting=tutorial&amp;status=1">Tell me
    more..</a></p>
<?php
        } else if ( ! get_bit( $t, 2 ) ) {
?>
<h2>That's it!</h2>
<p class="lead">You're ready to start cleaning up the mess.</p>
<p>Remember, if you ever need help, just look to the navigation bar.
Check your daily missions, and make sure that your mech is in tip-top
shape so that you aren't caught off-guard by one of the horrors coming
through the rifts.</p>
<p>Good luck!</p>
<?php
            update_character_meta( $character[ 'id' ], cr_meta_type_character,
                CR_TUTORIAL_STATUS,
                set_bit( $t, TUTORIAL_STATUS_BIT_COMPLETE ) ) ;
        } else {
            /* This is bad!  Clear the tutorial to be safe. */
            update_character_meta( $character[ 'id' ], cr_meta_type_character,
                CR_TUTORIAL_STATUS,
                set_bit( $t, TUTORIAL_STATUS_BIT_COMPLETE ) ) ;
        }
    }
}

add_action( 'do_page_content', 'cr_tutorial_print' );


function cr_tutorial_setting( $args ) {
    if ( ! isset( $args[ 'status' ] ) ) {
        return;
    }

    $bit = intval( $args[ 'status' ] );
    if ( ( $bit < 0 ) || ( $bit > 15 ) ) {
        return;
    }

    global $character;

    $t = character_meta( cr_meta_type_character, CR_TUTORIAL_STATUS );
    ensure_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_TUTORIAL_STATUS );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_TUTORIAL_STATUS, set_bit( $t, $bit ) );
}

$custom_setting_map[ 'tutorial' ] = 'cr_tutorial_setting';
