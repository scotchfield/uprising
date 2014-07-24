<?php

function cr_zone_crime() {
    global $character;

?>
<div class="row">
  <div class="col-md-6">
    <h2>Commit a Crime</h2>
  </div>
  <div class="col-md-6 text-right">

  </div>
</div>
<div class="row">
<p class="lead">To get ahead in this city, sometimes you have to break
a few laws.</p>
</div>
<?php

    $crime_obj = get_game_meta_keytype( cr_game_meta_crimes );

    foreach ( $crime_obj as $crime ) {
        $crime[ 'meta_value' ] = explode_meta( $crime[ 'meta_value' ] );
        if ( ( isset( $crime[ 'meta_value' ][ 'xp_needed' ] ) ) &&
             ( floatval( $crime[ 'meta_value' ][ 'xp_needed' ] ) >
               floatval( $character[ 'meta' ][ cr_meta_type_character ][
                   CR_CHARACTER_XP ] ) ) ) {
            continue;
        }
        echo( '<h3><a href="game-setting.php?setting=commit_crime&id=' .
              $crime[ 'meta_key' ] . '">' . $crime[ 'meta_value' ][ 'title' ] .
              '</a></h3>' );
    }

}


function cr_commit_crime( $args ) {
    global $character;

    if ( ! isset( $args[ 'id' ] ) ) {
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

    $xp = floatval( $character[ 'meta' ][ cr_meta_type_character ][
                    CR_CHARACTER_XP ] );
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

    if ( $character[ 'meta' ][ cr_meta_type_character ][
             CR_CHARACTER_STAMINA ] < $meta[ 'stamina' ] ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You\'re too tired to pull off that crime ' .
            'right now. Try again when you have more stamina!' );

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
            $new_money = intval( character_meta( cr_meta_type_character,
                CR_CHARACTER_MONEY ) ) + $money;

            update_character_meta( $character[ 'id' ], cr_meta_type_character,
                CR_CHARACTER_MONEY, $new_money );
            array_push( $tip_obj, 'You gain ' . $money . ' dollars.' );
        }

        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, join( ' ', $tip_obj ) );
    } else {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You try to commit the crime, but you\'re ' .
            'stopped!' );
    }

    $stamina = $character[ 'meta' ][ cr_meta_type_character ][
        CR_CHARACTER_STAMINA ] - $meta[ 'stamina' ];
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_STAMINA, $stamina );
}

$custom_setting_map[ 'commit_crime' ] = 'cr_commit_crime';
