<?php

function cr_select_check() {
    global $user, $character;

    if ( FALSE == $user ) {
        return;
    }

    if ( FALSE == $character ) {
        game_set_action( 'select' );
    }
}

add_action( 'action_set', 'cr_select_check' );


function cr_select_print() {
    global $user;

    if ( strcmp( 'select', game_get_action() ) ) {
       return;
    }

    $char_obj = get_characters_for_user( $user[ 'id' ] );
?>
<div class="row">
  <div class="col-md-2">
    &nbsp;
  </div>
  <div class="col-md-8">

<h1 class="text-center">Welcome back,
<?php echo( $user[ 'user_name' ] ); ?>.</h1>

  <h1 class="page_section">SELECT A CHARACTER</h1>
<!--<h2 class="text-center">Select a character:</h2>-->

<?php
    if ( count( $char_obj ) == 0 ) {
        echo( '<h3 class="text-center">None found!</h3>' );
    } else {
        foreach ( $char_obj as $char ) {
            echo( '<h3 class="text-center">' .
                  '<a href="game-setting.php?setting=select_character' .
                  '&amp;id=' . $char[ 'id' ] . '">' .
                  $char[ 'character_name' ] . '</a></h3>' );
        }
    }

    if ( count( $char_obj ) < $user[ 'max_characters' ] ) {
?>
<h1 class="text-center">Create a character</h1>
<form name="char_form" id="char_form" method="get" action="game-setting.php">
<div class="form-group">
<label>Character Name</label>
<input class="form-control" name="char_name" id="char_name" value="" type="text">
</div>
<button type="submit" class="btn btn-default">Let's go!</button>
<input type="hidden" name="setting" value="new_character">
</form>
<?php
    }
?>
  </div>
  <div class="col-md-2">

  </div>
</div>
<?php
}

add_action( 'do_page_content', 'cr_select_print' );