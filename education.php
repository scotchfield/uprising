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
        $degree_meta = json_decode( $degree[ 'meta_value' ], TRUE );
        echo( '<h3>' . $degree_meta[ 'title' ] . '</h3>' );
        echo( '<ul>' );
        foreach ( $course_obj as $course ) {
            $course_meta = json_decode( $course[ 'meta_value' ], TRUE );

            if ( $course_meta[ 'degree' ] == $degree[ 'meta_key' ] ) {
                echo( '<li>' . $course_meta[ 'title' ] . '</li>' );
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

    $course_meta = json_decode( $course[ 'meta_value' ], TRUE );

    return $course;
}
