<?php

require_once 'app.php';

require_login();

$state = get_state();

echo json_encode($state);