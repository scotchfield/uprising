<?php

function cr_casino_content() {
    global $character;

    if ( strcmp( 'casino', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-md-6">
    <h2>Casino</h2>
  </div>
  <div class="col-md-6 text-right">

  </div>
</div>
<div class="row">
<p class="lead">Feeling lucky?</p>
</div>
<?php


}

add_action( 'do_page_content', 'cr_casino_content' );
