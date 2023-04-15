<?php

require_once 'app.php';

require_login();

$id = $_POST['id'];
$active = $_POST['active'] == 'true' ? 1 : 0;

$stmt = $db->prepare("UPDATE switch SET active = :active WHERE id = :id");
$stmt->execute([
    'active' => $active,
    'id' => $id
]);