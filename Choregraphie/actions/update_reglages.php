<?php
session_start();

$weekendDebut = filter_input(INPUT_POST, 'weekend_debut', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$weekendFin = filter_input(INPUT_POST, 'weekend_fin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$semaineDebut = filter_input(INPUT_POST, 'semaine_debut', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$semaineFin = filter_input(INPUT_POST, 'semaine_fin', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


if (strtotime($weekendFin) <= strtotime($weekendDebut)) {
    header("Location: ../reglages.php?error=weekend");
    exit();
}

if (strtotime($semaineFin) <= strtotime($semaineDebut)) {
    header("Location: ../reglages.php?error=semaine");
    exit();
}

include "../includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME . ';charset=utf8mb4',
    config::USER, config::PASSWORD);

$reqWeekend = $pdo->prepare("UPDATE reglages_horaires SET HeureDebut = :debut, HeureFin = :fin WHERE Type = 'weekend'");
$reqWeekend->bindParam(':debut', $weekendDebut);
$reqWeekend->bindParam(':fin', $weekendFin);
$reqWeekend->execute();

$reqSemaine = $pdo->prepare("UPDATE reglages_horaires SET HeureDebut = :debut, HeureFin = :fin WHERE Type = 'semaine'");
$reqSemaine->bindParam(':debut', $semaineDebut);
$reqSemaine->bindParam(':fin', $semaineFin);
$reqSemaine->execute();

header("Location: ../reglages.php?updated=1");
exit();
?>