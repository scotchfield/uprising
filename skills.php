<?php

function cr_skills_content() {
    global $character;

    if ( strcmp( 'skills', game_get_action() ) ) {
       return;
    }

?>
<div class="row text-right">
  <h1 class="page_section">Skills</h1>
</div>

<div class="row">
  <p class="lead">Zone Description.</p>
</div>
<?php

}

add_action( 'do_page_content', 'cr_skills_content' );
