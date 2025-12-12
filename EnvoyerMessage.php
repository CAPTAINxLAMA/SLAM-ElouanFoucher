<?php
require 'vendor/autoload.php';

use Php\vendor\bluerhinos\phpmqtt\phpMQTT;

$server = '172.16.112.1';
$port = 1883;
$clientId = 'php-sender-' . rand();

$mqtt = new phpMQTT($server, $port, $clientId);

if ($mqtt->connect()) {
    $mqtt->publish('test/topic', 'Follow CAPTAINxLAMA on Twitch !!!', 0);
    $mqtt->close();
    echo "Message envoyé !";
} else {
    echo "Connexion MQTT ratée. Vérifie ta VM avant d'accuser le code.\n";
}