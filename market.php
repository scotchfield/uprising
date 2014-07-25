<?php

function cr_market_content() {
    global $character;

    if ( strcmp( 'market', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-xs-12">
    <h3>Market</h3>
    <p class="lead">Zone Description.</p>
  </div>
</div>
<?php

}

add_action( 'do_page_content', 'cr_market_content' );
