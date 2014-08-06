<?php

function cr_crime_content() {
    global $character;

    if ( strcmp( 'crime', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Crime</h1>
</div>
<div class="row">
<p class="lead">The <span class="crimson">Crimson Revolution</span> grow
stronger each day. Your help is needed to stop them.</p>
</div>
<?php

    $crime_obj = get_game_meta_keytype( cr_game_meta_crimes );
    $state_obj = get_game_meta_keytype( cr_game_meta_state );

    foreach ( $crime_obj as $crime ) {
        $meta = explode_meta( $crime[ 'meta_value' ] );
        if ( ( isset( $meta[ 'xp_needed' ] ) ) &&
             ( floatval( $meta[ 'xp_needed' ] ) >
               character_meta_float( cr_meta_type_character,
                   CR_CHARACTER_XP ) ) ) {
            continue;
        }
        if ( ! cr_crime_min_gamestate( $meta ) ) {
            continue;
        }
        echo( '<h3><a href="game-setting.php?setting=commit_crime&id=' .
              $crime[ 'meta_key' ] . '">' . $meta[ 'title' ] .
              '</a>' );
        if ( isset( $meta[ 'min_gamestate' ] ) ) {
            echo( ' (' .
                  $state_obj[ $meta[ 'min_gamestate' ] ][ 'meta_value' ] .
                  ' remaining)' );
        }
        echo( '</h3>' );
    }

}

add_action( 'do_page_content', 'cr_crime_content' );

function cr_crime_min_gamestate( $meta ) {
    if ( ! isset( $meta[ 'min_gamestate' ] ) ) {
        return TRUE;
    }

    $game_meta = get_game_meta( cr_game_meta_state, $meta[ 'min_gamestate' ] );
    if ( intval( $game_meta[ 'meta_value' ] ) <= 0 ) {
        return FALSE;
    }

    return TRUE;
}

function cr_commit_crime( $args ) {
    global $character;

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=crime';

    if ( ! isset( $args[ 'id' ] ) ) {
        return;
    }

    $buff_time = check_buff( $args[ 'id' ] );
    if ( $buff_time > 0 ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You\'re still undercover from the last ' .
            'crime! Try again in ' . time_round( $buff_time ) . '.' );
        return;
    }

    $crime = get_game_meta( cr_game_meta_crimes, $args[ 'id' ] );

    if ( FALSE == $crime ) {
        return;
    }

    $meta = explode_meta( $crime[ 'meta_value' ] );

    if ( ! isset( $meta[ 'stamina' ] ) ) {
        return;
    }

    $xp = character_meta_float( cr_meta_type_character, CR_CHARACTER_XP );
    $success = FALSE;

    $float_obj = array(
        'stamina', 'xp_always_succeed_until',
        'xp_min', 'xp_min_success', 'xp_max', 'xp_max_success',
        'xp_award_min', 'xp_award_max', 'xp_needed',
        'money_reward_min', 'money_reward_max',
    );
    foreach ( $float_obj as $f ) {
        if ( isset( $meta[ $f ] ) ) {
            $meta[ $f ] = floatval( $meta[ $f ] );
        }
    }

    if ( character_meta_float( cr_meta_type_character,
             CR_CHARACTER_STAMINA ) < $meta[ 'stamina' ] ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You\'re too tired to pull off that crime ' .
            'right now. Try again when you have more stamina!' );

        return;
    }

    if ( ! cr_crime_min_gamestate( $meta ) ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'That crime isn\'t available now!' );

        return;
    }

    if ( ( isset( $meta[ 'xp_always_succeed_until' ] ) ) &&
         ( $xp < $meta[ 'xp_always_succeed_until' ] ) ) {
        $success = TRUE;
    } else if ( $xp < $meta[ 'xp_min' ] ) {
        $success = ( mt_rand() / mt_getrandmax() ) < $meta[ 'xp_min_success' ];
    } else if ( $xp > intval( $meta[ 'xp_max' ] ) ) {
        $success = ( mt_rand() / mt_getrandmax() ) < $meta[ 'xp_max_success' ];
    } else {
        $pos = ( $xp - $meta[ 'xp_min' ] ) /
               ( $meta[ 'xp_max' ] - $meta[ 'xp_min' ] );
        $chance = $meta[ 'xp_min_success' ] + $pos *
            ( $meta[ 'xp_max_success' ] - $meta[ 'xp_min_success' ] );
        $success = ( mt_rand() / mt_getrandmax() ) < $chance;
    }

    if ( $success ) {
        $xp_award_min = floatval( $meta[ 'xp_award_min' ] );
        $xp_award_max = floatval( $meta[ 'xp_award_max' ] );
        $xp_award = $xp_award_min + ( mt_rand() / mt_getrandmax() ) *
            ( $xp_award_max - $xp_award_min );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_XP, $xp + $xp_award );

        $tip_obj = array(
            $meta[ 'text' ],
            'You succeed!',
            'You gain some experience.'
        );

        if ( isset( $meta[ 'money_reward_min' ] ) ) {
            $money = mt_rand(
                $meta[ 'money_reward_min' ], $meta[ 'money_reward_max' ] );
            $new_money = character_meta_int( cr_meta_type_character,
                CR_CHARACTER_MONEY ) + $money;

            update_character_meta( $character[ 'id' ], cr_meta_type_character,
                CR_CHARACTER_MONEY, $new_money );
            array_push( $tip_obj, 'You gain ' . $money . ' dollars.' );
        }

        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, join( ' ', $tip_obj ) );

        if ( isset( $meta[ 'min_gamestate' ] ) ) {
            $game_meta = get_game_meta(
                cr_game_meta_state, $meta[ 'min_gamestate' ] );
            $new_value = intval( $game_meta[ 'meta_value' ] ) - 1;
            update_game_meta( cr_game_meta_state, $meta[ 'min_gamestate' ],
                $new_value );
        }
    } else {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You try to commit the crime, but you\'re ' .
            'stopped and thrown in jail!' );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_JAIL_TIME, time() + 300 );
    }

    $stamina = character_meta_float( cr_meta_type_character,
        CR_CHARACTER_STAMINA ) - $meta[ 'stamina' ];
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_STAMINA, $stamina );

    award_buff( 1, 10 );
}

$custom_setting_map[ 'commit_crime' ] = 'cr_commit_crime';
