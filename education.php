<?php

function cr_zone_education() {
    global $character;

?>
<div class="row">
  <div class="col-md-3">
    <h2>Education</h2>
  </div>
  <div class="col-md-9 text-right">

  </div>
</div>
<div class="row">
<p class="lead">Stand on the shoulders of giants. A solid education gives
you the tools to further your crime goals.</p>
</div>
<?php

    $degree_obj = get_game_meta_keytype( cr_game_meta_degrees );
    $course_obj = get_game_meta_keytype( cr_game_meta_courses );

    foreach ( $degree_obj as $degree ) {
        $degree[ 'meta_value' ] = explode_meta( $degree[ 'meta_value' ] );
        echo( '<h3>' . $degree[ 'meta_value' ][ 'title' ] . '</h3>' );
        echo( '<ul>' );
        foreach ( $course_obj as $course ) {
            $course[ 'meta_value' ] = explode_meta( $course[ 'meta_value' ] );

            if ( $course[ 'meta_value' ][ 'degree' ] ==
                 $degree[ 'meta_key' ] ) {
                echo( '<li>' . $course[ 'meta_value' ][ 'title' ] . '</li>' );
            }
        }
        echo( '</ul>' );
    }
}

