<?php

function cr_casino_content() {
    global $character;

    if ( strcmp( 'casino', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Casino</h1>
</div>
<div class="row">
<p class="lead">Feeling lucky?</p>
</div>
<?php


}

add_action( 'do_page_content', 'cr_casino_content' );
