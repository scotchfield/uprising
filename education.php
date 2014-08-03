<?php

function cr_education_content() {
    global $character;

    if ( strcmp( 'education', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Education</h1>
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

add_action( 'do_page_content', 'cr_education_content' );

function cr_get_course( $course_id ) {
    $course = get_game_meta( cr_game_meta_courses, $course_id );

    if ( FALSE == $course ) {
        return FALSE;
    }

    $course[ 'meta_value' ] = explode_meta( $course[ 'meta_value' ] );

    return $course;
}
