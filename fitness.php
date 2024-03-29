<?php


function cr_fitness_content() {
    global $character;

    if ( strcmp( 'fitness', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Fitness</h1>
</div>

<div class="row">
<p class="lead">Physical strength is just as important as mental ability.
Work out in the city's fitness centers, and boost your combat stats.</p>
</div>
<?php

    $gym_id = character_meta_int( cr_meta_type_character, CR_CHARACTER_GYM_ID );

    $gym = get_game_meta( cr_game_meta_gyms, $gym_id );
    $gym_meta = json_decode( $gym[ 'meta_value' ], TRUE );

?>
    <h3><a href="game-setting.php?setting=fitness_train&train=str">Train
        Strength</a></h3>
    <h3><a href="game-setting.php?setting=fitness_train&train=dex">Train
        Dexterity</a></h3>
    <h3><a href="game-setting.php?setting=fitness_train&train=int">Train
        Intelligence</a></h3>
    <h3><a href="game-setting.php?setting=fitness_train&train=con">Train
        Constitution</a></h3>
<?php

}

add_action( 'do_page_content', 'cr_fitness_content' );


function cr_fitness_train( $args ) {
    global $character;

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=fitness';

    $gym_id = character_meta_int( cr_meta_type_character, CR_CHARACTER_GYM_ID );

    $gym = get_game_meta( cr_game_meta_gyms, $gym_id );
    $gym_meta = json_decode( $gym[ 'meta_value' ], TRUE );

    $train_obj = array(
        'str' => CR_CHARACTER_STR,
        'dex' => CR_CHARACTER_DEX,
        'int' => CR_CHARACTER_INT,
        'con' => CR_CHARACTER_CON
    );

    if ( ( ! isset( $args[ 'train' ] ) ) ||
         ( ! isset( $train_obj[ $args[ 'train' ] ] ) ) ) {
        return;
    }

    $stamina = character_meta_float(
        cr_meta_type_character, CR_CHARACTER_STAMINA );
    if ( $stamina < 5 ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You\'re way too tired to exercise right ' .
            'now! Come back when you have some more stamina.' );

        return;
    }

    $stat = rand_float( $gym_meta[ 'stat_min' ], $gym_meta[ 'stat_max' ] );

    $new_stat = $stat + character_meta_float( cr_meta_type_character,
        $train_obj[ $args[ 'train' ] ] );

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        $train_obj[ $args[ 'train' ] ], $new_stat );

    $stamina = $stamina - 5;
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_STAMINA, $stamina );

    $stat = round( $stat, $precision = 2 );

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_TIP, 'You work out and gain ' . $stat .
        ' points to that stat!' );
}

$custom_setting_map[ 'fitness_train' ] = 'cr_fitness_train';
