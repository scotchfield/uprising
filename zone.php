<?php

function cr_zone_set() {
    global $character;

    if ( strcmp( 'zone', game_get_action() ) ) {
        return;
    }

    if ( ! ( isset( $_GET[ 'zone_id' ] ) || isset( $_GET[ 'zone_tag' ] ) ) ) {
        return;
    }

    $zone_id = 0;
    if ( isset( $_GET[ 'zone_tag' ] ) ) {
        $zone = get_zone_by_tag( $_GET[ 'zone_tag' ] );
        if ( FALSE != $zone ) {
            $zone_id = $zone[ 'id' ];
        }
    } else {
        $zone_id = intval( $_GET[ 'zone_id' ] );
    }

    if ( '' != character_meta( cr_meta_type_character, CR_CURRENT_ZONE ) ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CURRENT_ZONE, $zone_id );
    } else {
        add_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CURRENT_ZONE, $zone_id );
    }

    $character[ 'meta' ][ cr_meta_type_character ][ CR_CURRENT_ZONE ] =
        $zone_id;
}

add_action( 'action_set', 'cr_zone_set' );

function cr_zone_content() {
    global $character;

    if ( strcmp( 'zone', game_get_action() ) ) {
       return;
    }

    $zone_id = GAME_STARTING_ZONE;
    if ( '' != character_meta( cr_meta_type_character, CR_CURRENT_ZONE ) ) {
        $zone_id = character_meta( cr_meta_type_character, CR_CURRENT_ZONE );
    }

    $zone = get_zone( $zone_id );
    $zone_meta = json_decode( $zone[ 'zone_meta' ], TRUE );

    if ( ! strcmp( 'home', $zone[ 'zone_type' ] ) ) {
        echo( '<h2>My Home</h2>' );
    }

}

add_action( 'do_page_content', 'cr_zone_content' );

