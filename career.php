<?php

function cr_zone_career() {
    global $character;

    $job_id = intval( $character[ 'meta' ][ cr_meta_type_character ][
        CR_CHARACTER_JOB_ID ] );
    $job = FALSE;

    if ( 0 != $job_id ) {
        $job = get_game_meta( sc_game_meta_jobs, $job_id );
        $job[ 'meta_value' ] = explode_meta( $job[ 'meta_value' ] );
        $employer = get_game_meta(
            sc_game_meta_employers, $job[ 'meta_value' ][ 'employer' ] );
    }
?>
<div class="row">
  <div class="col-md-3">
    <h2>Career</h2>
  </div>
  <div class="col-md-9 text-right">
<?php
    if ( FALSE == $job ) {
        echo( '<h2><b>Unemployed</b></h2>' );
    } else {
        echo( '<h2><b>' . $job[ 'meta_value' ][ 'title' ] .
              '</b> at <b>' . $employer[ 'meta_value' ] . '</b></h2>' );
    }
?>
  </div>
</div>
<div class="row">
<p class="lead">One of the easiest ways to make money is to find a reputable
career.  As you progress through the company ranks, you'll make more money,
earn additional bonuses, and command more respect.</p>
</div>
<?php

    if ( 0 == $job_id ) {
        /*echo( '<h3>You are unemployed! You can find an entry-level career ' .
              'below.</h3>' );

        $employer_obj = get_game_meta_keytype( sc_game_meta_employers );
        $job_obj = get_game_meta_keytype( sc_game_meta_jobs );

        foreach ( $job_obj as $k => $job ) {
            $job_obj[ $k ][ 'meta_value' ] = explode_meta(
                $job[ 'meta_value' ] );
        }

        foreach ( $employer_obj as $e ) {
            echo( '<h4>' . $e[ 'meta_value' ] . '</h4>' );
            foreach ( $job_obj as $k => $job ) {
                if ( $job[ 'meta_value' ][ 'employer' ] == $e[ 'meta_key' ] ) {
                    echo( '<p><a href="game-setting.php?' .
                          'setting=accept_career&career_id=' .
                          $job[ 'meta_key' ] . '">' .
                          $job[ 'meta_value' ][ 'title' ] . '</a></p>' );
                }
            }
        }*/
    } else {
        $job = get_game_meta( sc_game_meta_jobs, $job_id );
        $job[ 'meta_value' ] = explode_meta( $job[ 'meta_value' ] );
        $employer = get_game_meta(
            sc_game_meta_employers, $job[ 'meta_value' ][ 'employer' ] );

        echo( '<h4>Current job: <b>' . $job[ 'meta_value' ][ 'title' ] .
              '</b> at <b>' . $employer[ 'meta_value' ] . '</b> (Tier ' .
              $job[ 'meta_value' ][ 'tier' ] . ', $' .
              $job[ 'meta_value' ][ 'salary' ] . '/day)</h4>' );
    }
?>
<div class="row">
  <h3>Career Paths in Crime City</h3>
</div>
<div class="row">
  <div class="col-md-6">
    <h4>Semantic Code</h4>
    <p>The leading software development company in Crime City.  You'll
        learn a solid set of coding skills that will bring in a comfortable
        income while teaching you a new set of hacking crimes.</p>
    <p><a href="game-setting.php?setting=accept_career&career_id=1">Apply
        for an entry level job: Tester</a></p>
  </div>
  <div class="col-md-6">
    <h4>University</h4>
    <p>Education is important, and to keep the student money flowing into
        the esteemed halls of the university, competent educators and
        researchers are needed.</p>
    <p><a href="game-setting.php?setting=accept_career&career_id=11">Apply
        for an entry level job: Undergraduate</p>
  </div>
</div>
<?php
}


function cr_accept_career( $args ) {
    global $character;

    if ( ! isset( $args[ 'career_id' ] ) ) {
        return;
    }

    $job_id = intval( $character[ 'meta' ][ cr_meta_type_character ][
        CR_CHARACTER_JOB_ID ] );

    $new_job_id = intval( $args[ 'career_id' ] );
    $new_job = get_game_meta( sc_game_meta_jobs, $new_job_id );
    if ( FALSE == $new_job ) {
        return;
    }

    $new_job[ 'meta_value' ] = explode_meta( $new_job[ 'meta_value' ] );

    if ( ( 0 == $job_id ) && ( $new_job[ 'meta_value' ][ 'tier' ] == 1 ) ) {
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_JOB_ID, $new_job_id );
    } else if ( 0 != $job_id ) {
        $job = get_game_meta( sc_game_meta_jobs, $job_id );
        $job[ 'meta_value' ] = explode_meta( $job[ 'meta_value' ] );

        if ( $new_job[ 'meta_value' ][ 'tier' ] ==
                 $job[ 'meta_value' ][ 'tier' ] + 1 ) {
            debug_print( 'yes, can accept' );
        }
        debug_print( $new_job );
    } else {
        debug_print( 'can\'t accept this job' );
    }
}

$custom_setting_map[ 'accept_career' ] = 'cr_accept_career';
