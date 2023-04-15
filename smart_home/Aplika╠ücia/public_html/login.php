<?php

require_once 'app.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['login'] == 'smarthome' && $_POST['pass'] == 'smarthome') {
        $_SESSION['logged'] = true;
        redirect('index.php');
    }
    
    $error = 'Nesprávne prihlasovacie meno alebo heslo.';
}

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>SmartHome - prihlásenie</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
  </head>
  <body class="login-page">
    <div class="login">
      <h1>Smart Home</h1>
      <?php if ($error) { ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
      <?php } ?>
      <form action="" method="post">
        <div class="form-group">
          <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" name="login" class="form-control" placeholder="Prihlasovacie meno" />
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <i class="fa fa-key"></i>
            <input type="password" name="pass" class="form-control" placeholder="Heslo" />
          </div>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="fa fa-lock"></i>
          Prihlásenie
        </button>
      </form>
    </div>
  </body>
</html>