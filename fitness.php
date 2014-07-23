<?php


function cr_zone_fitness() {
    global $character;

?>
<div class="row">
  <div class="col-md-3">
    <h2>Fitness</h2>
  </div>
  <div class="col-md-9 text-right">

  </div>
</div>
<div class="row">
<p class="lead">Physical strength is just as important as mental ability.
Work out in the city's fitness centers, and boost your combat stats.</p>
</div>
<?php

    $gym_id = intval(
        character_meta( cr_meta_type_character, CR_CHARACTER_GYM_ID ) );

    $gym = get_game_meta( cr_game_meta_gyms, $gym_id );
    $gym[ 'meta_value' ] = explode_meta( $gym[ 'meta_value' ] );

    debug_print( $gym );
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


function cr_fitness_train( $args ) {
    global $character;

    $gym_id = intval(
        character_meta( cr_meta_type_character, CR_CHARACTER_GYM_ID ) );

    $gym = get_game_meta( cr_game_meta_gyms, $gym_id );
    $gym[ 'meta_value' ] = explode_meta( $gym[ 'meta_value' ] );

    debug_print( $gym );

    exit;
}

$custom_setting_map[ 'fitness_train' ] = 'cr_fitness_train';
