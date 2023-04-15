<?php

require_once 'app.php';

$temperature = $_GET['temperature'];
$humidity = $_GET['humidity'];

$stmt = $db->prepare("INSERT INTO dht11_sensorlog (temperature, humidity, date) VALUES (:temperature, :humidity, :date)");
$stmt->execute([
    'temperature' => $temperature,
    'humidity' => $humidity,
    'date' => date('Y-m-d H:i:s')
]);

$state = 0;

$switches = $db->query("SELECT * FROM switch")->fetchAll();

foreach ($switches as $switch) {
    if ($switch['active'] == 1) {
        $state |= $switch['bitmask'];
    }
}

echo $state;
