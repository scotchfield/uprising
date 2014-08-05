<?

function cr_map_print() {
    global $user, $character;

    if ( strcmp( 'map', game_get_action() ) ) {
        return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">State of the City</h1>
</div>
<div class="row">

</div>
<?php
}

add_action( 'do_page_content', 'cr_map_print' );
