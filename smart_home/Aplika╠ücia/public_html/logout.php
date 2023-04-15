<?php

require_once 'app.php';

require_login();

$_SESSION['logged'] = false;

redirect('login.php');