<?php

function cr_career_content() {
    global $character;

    if ( strcmp( 'career', game_get_action() ) ) {
       return;
    }

    $job_id = character_meta_int( cr_meta_type_character, CR_CHARACTER_JOB_ID );
    $job = FALSE;

    if ( 0 != $job_id ) {
        $job = cr_get_job( $job_id );
        $employer = get_game_meta(
            cr_game_meta_employers, $job[ 'meta_value' ][ 'employer' ] );
    }
?>
<div class="row text-right">
  <h1 class="page_section">Career</h1>
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

        $employer_obj = get_game_meta_keytype( cr_game_meta_employers );
        $job_obj = get_game_meta_keytype( cr_game_meta_jobs );

        foreach ( $job_obj as $k => $job ) {
            $job_obj[ $k ][ 'meta_value' ] = json_decode(
                $job[ 'meta_value' ], TRUE );
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
        echo( '<h4>Current job: <b>' . $job[ 'meta_value' ][ 'title' ] .
              '</b> at <b>' . $employer[ 'meta_value' ] . '</b> (Tier ' .
              $job[ 'meta_value' ][ 'tier' ] . ', $' .
              $job[ 'meta_value' ][ 'salary' ] . '/day)</h4>' );

        if ( isset( $job[ 'meta_value' ][ 'promote_after' ] ) ) {
            $promote_time = character_meta( cr_meta_type_character,
                CR_CHARACTER_JOB_HIRED ) + 
                $job[ 'meta_value' ][ 'promote_after' ];
            if ( time() < $promote_time ) {
                $promote_left = $promote_time - time();
                echo( '<h5>Time until promotion: ' .
                      time_expand( $promote_left ) . '</h5>' );
            } else {
                echo( '<h5>' .
                      '<a href="game-setting.php?setting=promote_career">' .
                      'Apply for a promotion!</a></h5>' );
            }

            $promote_job = cr_get_job( $job[ 'meta_value' ][ 'promote_job' ] );

            echo( '<h5>Next promotion: <b>' .
                  $promote_job[ 'meta_value' ][ 'title' ] . '</b> at <b>' .
                  $employer[ 'meta_value' ] . '</b> (Tier ' .
                  $promote_job[ 'meta_value' ][ 'tier' ] . ', $' .
                  $promote_job[ 'meta_value' ][ 'salary' ] . '/day)</h5>' );
        }

        if ( isset( $_GET[ 'leave_confirm' ] ) ) {
            echo( '<h3><a href="game-setting.php?setting=leave_career&' .
                  'confirm=' . nonce_create( 'leave_confirm' ) . '">' .
                  'You\'re quitting? Are you sure? This can\'t be ' .
                  'undone!</a></h3>' );
        } else {
            echo( '<h5><a href="game-setting.php?setting=leave_career">' .
                  'Leave this job for something new?</a></h5>' );
        }
    }
?>
<div class="row">
  <h3>Career Paths</h3>
</div>
<div class="row">
  <div class="col-md-6">
    <h4>Semantic Code</h4>
    <p>The leading software development company in Lancaster.  You'll
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

add_action( 'do_page_content', 'cr_career_content' );

function cr_get_job( $job_id ) {
    $job = get_game_meta( cr_game_meta_jobs, $job_id );

    if ( FALSE == $job ) {
        return FALSE;
    }

    $job[ 'meta_value' ] = json_decode( $job[ 'meta_value' ], TRUE );

    return $job;
}

function cr_award_salary() {
    global $character;

    $job_id = character_meta_int( cr_meta_type_character, CR_CHARACTER_JOB_ID );
    $job = cr_get_job( $job_id );

    if ( FALSE == $job ) {
        return;
    }

    $time = cr_career_tick();
    $last_paid = character_meta_int( cr_meta_type_character,
        CR_CHARACTER_JOB_LASTPAID );

    if ( $time <= $last_paid ) {
        return;
    }

    $money = character_meta_int( cr_meta_type_character,
        CR_CHARACTER_MONEY ) + intval( $job[ 'meta_value' ][ 'salary' ] );

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_MONEY, $money );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_TIP, 'You put in a hard day of work and earn ' .
        $job[ 'meta_value' ][ 'salary' ] . ' dollars.' );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_LASTPAID, cr_career_tick() );
}

function cr_career_tick() {
    return floor( time() / SECONDS_DAY );
}

function cr_accept_career( $args ) {
    global $character;

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=career';

    if ( ! isset( $args[ 'career_id' ] ) ) {
        return;
    }

    $job_id = character_meta_int(
        cr_meta_type_character, CR_CHARACTER_JOB_ID );

    $new_job_id = intval( $args[ 'career_id' ] );
    $new_job = get_game_meta( cr_game_meta_jobs, $new_job_id );
    if ( FALSE == $new_job ) {
        return;
    }

    $new_job[ 'meta_value' ] = json_decode( $new_job[ 'meta_value' ], TRUE );

    if ( ( 0 == $job_id ) && ( $new_job[ 'meta_value' ][ 'tier' ] == 1 ) ) {

        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_JOB_ID, $new_job_id );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_JOB_HIRED, time() );
        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_JOB_LASTPAID, cr_career_tick() );

        award_achievement( 1 );

    } else {

        update_character_meta( $character[ 'id' ], cr_meta_type_character,
            CR_CHARACTER_TIP, 'You already have a job!<br>If you want ' .
            'to change careers, you have to leave your old job first.' );

    }
}

$custom_setting_map[ 'accept_career' ] = 'cr_accept_career';

function cr_leave_career( $args ) {
    global $character;

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=career';

    $job_id = character_meta_int(
        cr_meta_type_character, CR_CHARACTER_JOB_ID );

    if ( 0 == $job_id ) {
        return;
    }

    if ( ! isset( $args[ 'confirm' ] ) ) {
        $GLOBALS[ 'redirect_header' ] = GAME_URL .
            '?action=career&leave_confirm';
        return;
    }

    if ( ! nonce_verify( $args[ 'confirm' ], 'leave_confirm' ) ) {
        return;
    }

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_ID, 0 );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_HIRED, 0 );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_LASTPAID, 0 );
}

$custom_setting_map[ 'leave_career' ] = 'cr_leave_career';


function cr_promote_career( $args ) {
    global $character;

    $GLOBALS[ 'redirect_header' ] = GAME_URL . '?action=career';

    $job_id = character_meta_int(
        cr_meta_type_character, CR_CHARACTER_JOB_ID );

    if ( 0 == $job_id ) {
        return;
    }

    $job = cr_get_job( $job_id );

    if ( ! isset( $job[ 'meta_value' ][ 'promote_after' ] ) ) {
        return;
    }

    $promote_time = character_meta( 
        cr_meta_type_character, CR_CHARACTER_JOB_HIRED ) +
        $job[ 'meta_value' ][ 'promote_after' ];

    if ( time() < $promote_time ) {
        return;
    }

    $promote_job = cr_get_job( $job[ 'meta_value' ][ 'promote_job' ] );

    if ( FALSE == $promote_job ) {
        return;
    }

    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_ID, $job[ 'meta_value' ][ 'promote_job' ] );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_JOB_HIRED, time() );
    update_character_meta( $character[ 'id' ], cr_meta_type_character,
        CR_CHARACTER_TIP, 'The promotion is yours, you\'ve earned it! ' .
        'As of now, your new job title is <b>' .
        $promote_job[ 'meta_value' ][ 'title' ] . '</b>!' );
}

$custom_setting_map[ 'promote_career' ] = 'cr_promote_career';

