<?php
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
var_dump($id );
include_once "../includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME, config::USER, config::PASSWORD);

$reqChore = $pdo->prepare("SELECT * FROM choregraphies WHERE Id = :id");
$reqChore->bindParam(':id', $id);
$reqChore->execute();
$choree = $reqChore->fetch();

if (!$choree) {
    die("Chorégraphie introuvable");
}

$reqMvt = $pdo->prepare(" SELECT m.MvtAngle, m.MvtTime FROM choregraphie_mouvements cm JOIN mouvements m ON cm.Mouvement_Id = m.Id WHERE cm.Choregraphie_Id = :id ORDER BY cm.Ordre");
$reqMvt->bindParam(':id', $id);
$reqMvt->execute();
$mouvements = $reqMvt->fetchAll(PDO::FETCH_ASSOC);

$reqAff = $pdo->prepare(" SELECT a.AffText, a.AffTime FROM choregraphie_affichages ca JOIN affichages a ON ca.Affichage_Id = a.Id WHERE ca.Choregraphie_Id = :id ORDER BY ca.Ordre");
$reqAff->bindParam(':id', $id);
$reqAff->execute();
$affichages = $reqAff->fetchAll(PDO::FETCH_ASSOC);

$reqSon = $pdo->prepare(" SELECT s.SonNote, s.SonVolume FROM choregraphie_sons cs JOIN sons s ON cs.Son_Id = s.Id WHERE cs.Choregraphie_Id = :id ORDER BY cs.Ordre");
$reqSon->bindParam(':id', $id);
$reqSon->execute();
$sons = $reqSon->fetchAll(PDO::FETCH_ASSOC);

$choreData = [
    'id' => $choree['Id'],
    'nom' => $choree['ChoreName'],
    'mouvements' => $mouvements,
    'affichages' => $affichages,
    'sons' => $sons
];

$messageJson = json_encode($choreData, JSON_PRETTY_PRINT);

require '../vendor/autoload.php';

use Bluerhinos\phpMQTT;

$server = 'mqtt.latetedanslatoile.fr';
$port = 1883;
$clientId = 'php-sender-' . rand();
$username = 'Epsi';
$password = 'EpsiWis2018!';

$mqtt = new phpMQTT($server, $port, $clientId);

if ($mqtt->connect(true, null, $username, $password)) {
    $mqtt->publish('Bisik', $messageJson, 0);
    $mqtt->close();
    echo "Message envoyé !";
} else {
    echo "Connexion MQTT ratée. Vérifie ta VM avant d'accuser le code.\n";
}

header("Location: ../index.php?tested=1&chore=" . urlencode($choree['ChoreName']));
exit();