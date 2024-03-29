<?php

function cr_title_content() {
    global $character;

    if ( strcmp( 'title', game_get_action() ) ) {
        return;
    }

    if ( FALSE != $character ) {
        return;
    }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo( GAME_NAME ); ?></title>
    <link rel="stylesheet" href="<?php echo( GAME_CUSTOM_STYLE_URL );
        ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo( GAME_CUSTOM_STYLE_URL );
        ?>elysian.css">
    <link href="http://fonts.googleapis.com/css?family=Raleway:400,500"
          rel="stylesheet" type="text/css">
  </head>
  <body>

<div class="container">

  <div class="row">
    <div class="col-md-8">
      <img src="<?php echo( GAME_CUSTOM_STYLE_URL ); ?>elysian.png" width="100%">
    </div>
    <div class="col-md-4">

      <form class="form-horizontal" role="form" name="login_form"
            id="login_form" method="post" action="game-login.php">
        <div class="form-group">
          <label for="login_user"
                 class="col-sm-4 control-label">Username</label>
          <div class="col-sm-8">
            <input class="form-control input-sm" name="user"
                   id="login_user" value="" type="text">
          </div>
        </div>
        <div class="form-group">
          <label for="login_pass"
                 class="col-sm-4 control-label">Password</label>
          <div class="col-sm-8">
            <input class="form-control" name="pass"
                   id="login_pass" value="" type="password">
          </div>
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-sm btn-default">Log in!</button>
        </div>
        <input type="hidden" name="action" value="login">
      </form>

    </div>
  </div>

<?php
$err_obj = array(
    1 => 'Please provide a username.',
    2 => 'Please provide a password.',
    3 => 'Please provide a valid email address.',
    4 => 'That username already exists.',
    5 => 'That email address is already in use.',
    6 => 'That username and password combination does not exist.',
    100 => 'Thanks! Please check your email for a validation link.',
    101 => 'That account is already validated!',
    102 => 'Success! You can now log in.',
);

if ( isset( $_GET[ 'notify' ] ) ) {
    $notify = intval( $_GET[ 'notify' ] );
    if ( isset( $err_obj[ $notify ] ) ) {
        echo( '<div class="row text-center"><h2>' .
              $err_obj[ $notify ] . '</h2></div>' );
    }
}
?>

  <div class="row">

    <div class="col-md-8">
      <h3>A City Under Siege!</h3>
      <p>The city of Lancaster has slowly descended into the hands of the
        crooked. A powerful gang known as the XY have
        influence over everyone with power, including the
        politicians, the police force, and the large corporations in town.
        With their own crypto-currency taking over the economic landscape,
        the XY seem unstoppable.</p>
      <h3>Rise up and take the power back!</h3>
      <p>As a new recruit in the Elysian Uprising, you will join the
        outsiders who commit subversive acts to bring down the criminals
        taking over the city. By sabotaging the XY's interests,
        obtaining a job that gives you access to the gang, and a wide
        range of other actions, you will become a force for good, even if
        it means you're branded a villain.</p>
      <h3>Always free</h3>
      <p><?php echo( GAME_NAME ); ?> is free to play, and we are devoted
        to keeping it that way. Although we offer microtransactions that
        can expose new area or allow characters to distinguish themselves,
        <?php echo( GAME_NAME ); ?> is not a "pay-to-win" game. Purchases
        are strictly optional, and there will always be ways to obtain the
        same items without payment.</p>
    </div>
    <div class="col-md-4">

    </div>

  </div>

  <div class="row">

    <div class="col-md-6">
    </div>

    <div class="col-md-6">
      <h3 class="text-right">Register for a free account</h3>

      <form class="form-horizontal" name="register_form" id="register_form"
            method="post" action="game-login.php">
        <div class="form-group">
          <label for="register_user"
                 class="col-sm-4 control-label">Username</label>
          <div class="col-sm-8">
            <input class="form-control input-sm" name="user"
                   id="register_user" value="" type="text">
          </div>
        </div>
        <div class="form-group">
          <label for="register_pass"
                 class="col-sm-4 control-label">Password</label>
          <div class="col-sm-8">
            <input class="form-control" name="pass"
                   id="register_pass" value="" type="password">
          </div>
        </div>
        <div class="form-group">
          <label for="register_email"
                 class="col-sm-4 control-label">Email</label>
          <div class="col-sm-8">
            <input class="form-control" name="email"
                   id="register_email" value="" type="text">
          </div>
        </div>
        <div class="text-right">
          <button type="submit"
                  class="btn btn-sm btn-default">Register</button>
        </div>
        <input type="hidden" name="action" value="register">
      </form>

    </div>
  </div>

</div>

<script src="<?php echo GAME_CUSTOM_STYLE_URL; ?>jquery.min.js"></script>
<script src="<?php echo GAME_CUSTOM_STYLE_URL; ?>bootstrap.min.js"></script>

</body>
</html>

<?
}

add_action( 'do_page_content', 'cr_title_content' );
