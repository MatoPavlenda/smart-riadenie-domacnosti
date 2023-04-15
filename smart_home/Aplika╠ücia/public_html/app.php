<?php

if (strpos($_SERVER['REQUEST_URI'], 'dht11.php') === false) {
    session_start();
}

try {
    $db = new PDO('mysql:host=45.84.205.153;dbname=u290703321_nodemcu_datalo', 'u290703321_esp_data', 'ESP8266dht');

} catch (Exception $e) {
    die('database error');
}

$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$db->exec('SET character_set_results=utf8');
$db->exec('SET character_set_connection=utf8');
$db->exec('SET character_set_client=utf8');

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function require_login()
{
    if (empty($_SESSION['logged']) || $_SESSION['logged'] !== true) {
        redirect('login.php');
    }
}

function get_state()
{
    global $db;
    
    $state = $db->query(
        "SELECT 
          temperature, 
          humidity, 
          date 
        FROM dht11_sensorlog 
        ORDER BY id DESC 
        LIMIT 1"
    )->fetch();
    
    if (!$state) {
        return [
            'temperature' => null,
            'humidity' => null,
            'date' => null,
        ];
    }

    return [
        'temperature' => (float) $state['temperature'],
        'humidity' => (float) $state['humidity'],
        'date' => $state['date'],
    ];
}
