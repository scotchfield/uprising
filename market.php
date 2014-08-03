<?php

function cr_market_content() {
    global $character;

    if ( strcmp( 'market', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Market</h1>
</div>
<div class="row">
  <p class="lead">Zone Description.</p>
</div>
<?php

}

add_action( 'do_page_content', 'cr_market_content' );
