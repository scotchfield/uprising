<?php

function cr_residence_content() {
    global $character;

    if ( strcmp( 'residence', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-xs-12">
    <h2>Residence</h2>
    <p class="lead">Zone Description.</p>
  </div>
</div>
<?php

}

add_action( 'do_page_content', 'cr_residence_content' );
