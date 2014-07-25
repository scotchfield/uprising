<?php

function cr_skills_content() {
    global $character;

    if ( strcmp( 'skills', game_get_action() ) ) {
       return;
    }

?>
<div class="row">
  <div class="col-xs-12">
    <h3>Skills</h3>
    <p class="lead">Zone Description.</p>
  </div>
</div>
<?php

}

add_action( 'do_page_content', 'cr_skills_content' );
