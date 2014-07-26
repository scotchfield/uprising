<?php

function cr_status_content() {
    global $character;

    if ( strcmp( 'status', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-xs-12">
    <h2>State of the City</h2>
    <p class="lead">Zone Description.</p>
  </div>
</div>
<?php

}

add_action( 'do_page_content', 'cr_status_content' );
