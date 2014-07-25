<?php

function cr_market_content() {
    global $character;

    if ( strcmp( 'market', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-xs-12">
    <h2>Market</h2>
    <p class="lead">Zone Description.</p>
  </div>
</div>
<?php

}

add_action( 'do_page_content', 'cr_market_content' );
