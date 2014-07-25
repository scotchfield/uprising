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
    //$zone_transitions = get_zone_transitions( $zone_id );
    $zone[ 'meta' ] = explode_meta( $zone[ 'zone_meta' ] );

    /*$npc_obj = array();
    if ( isset( $zone[ 'meta' ][ 'npcs' ] ) ) {
        $npc_id_obj = explode( ',', $zone[ 'meta' ][ 'npcs' ] );
        foreach ( $npc_id_obj as $npc_id ) {
            $npc_obj[ $npc_id ] = get_npc_by_id( $npc_id );
        }
    }

    echo( '<div class="row"><div class="col-xs-8">' .
          '<h3>' . $zone[ 'zone_title' ] . '</h3>' .
          '<p class="lead">' . $zone[ 'zone_description' ] . '</p>' .
          '</div><div class="col-xs-4">' );

    if ( count( $npc_obj ) > 0 ) {
        echo( '<h4 class="text-right">Others here</h4><ul>' );
        foreach ( $npc_obj as $zn ) {
            echo( '<li class="text-right"><a href="' . GAME_URL .
                  '?action=npc&amp;id=' .
                  $zn[ 'id' ] . '">' . $zn[ 'npc_name' ] .
                  '</a></li>' );
        }
        echo( '</ul>' );
    }

    echo '<h4 class="text-right">Go somewhere else</h4><ul>';
    foreach ( $zone_transitions as $zt ) {
        echo '<li class="text-right"><a href="' . GAME_URL .
             '?action=zone&amp;zone_tag=' .
             $zt[ 'zone_tag' ] . '">' . $zt[ 'zone_title' ] .
             '</a></li>';
    }
    echo '</ul>';

    echo( '</div></div>' );*/

    if ( ! strcmp( 'home', $zone[ 'zone_type' ] ) ) {
        echo( '<h2>My Home</h2>' );
    }

}

add_action( 'do_page_content', 'cr_zone_content' );

