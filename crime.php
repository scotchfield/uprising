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
    debug_print( $crime_obj );

    foreach ( $crime_obj as $crime ) {
        $crime[ 'meta_value' ] = explode_meta( $crime[ 'meta_value' ] );
        echo( '<h3><a href="game-setting.php?setting=commit_crime&id=' .
              $crime[ 'meta_key' ] . '">' . $crime[ 'meta_value' ][ 'title' ] .
              '</a></h3>' );
    }

}


function cr_commit_crime( $args ) {
    if ( ! isset( $args[ 'id' ] ) ) {
        return;
    }

    $crime = get_game_meta( cr_game_meta_crimes, $args[ 'id' ] );

    if ( FALSE == $crime ) {
        return;
    }

    $crime[ 'meta_value' ] = explode_meta( $crime[ 'meta_value' ] );

    debug_print( $crime );
    exit;
}

$custom_setting_map[ 'commit_crime' ] = 'cr_commit_crime';
