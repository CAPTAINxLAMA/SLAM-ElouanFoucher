<?php
session_start();
$tokenServeur=$_SESSION['token'];
$tokenRecu=filter_input(INPUT_POST,'token',FILTER_VALIDATE_INT);

if ($tokenRecu != $tokenServeur){
    die("Erreur de token, va mourir vilain hacker.");// je stoppe tout
}
$modifyId=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
include "../includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME , config::USER, config::PASSWORD);

$reqOldMvt = $pdo->prepare("SELECT Mouvement_Id FROM choregraphie_mouvements WHERE Choregraphie_Id = :id");
$reqOldMvt->bindParam(':id', $modifyId);
$reqOldMvt->execute();
$oldMvtIds = $reqOldMvt->fetchAll(PDO::FETCH_COLUMN);

$reqDeleteLinkMvt = $pdo->prepare("DELETE FROM choregraphie_mouvements WHERE Choregraphie_Id = :id");
$reqDeleteLinkMvt->bindParam(':id', $modifyId);
$reqDeleteLinkMvt->execute();

if (!empty($oldMvtIds)) {
    $placeholders = implode(',', array_fill(0, count($oldMvtIds), '?'));
    $reqDeleteMvt = $pdo->prepare("DELETE FROM mouvements WHERE Id IN ($placeholders)");
    $reqDeleteMvt->execute($oldMvtIds);
}

$reqOldAff = $pdo->prepare("SELECT Affichage_Id FROM choregraphie_affichages WHERE Choregraphie_Id = :id");
$reqOldAff->bindParam(':id', $modifyId);
$reqOldAff->execute();
$oldAffIds = $reqOldAff->fetchAll(PDO::FETCH_COLUMN);

$reqDeleteLinkAff = $pdo->prepare("DELETE FROM choregraphie_affichages WHERE Choregraphie_Id = :id");
$reqDeleteLinkAff->bindParam(':id', $modifyId);
$reqDeleteLinkAff->execute();

if (!empty($oldAffIds)) {
    $placeholders = implode(',', array_fill(0, count($oldAffIds), '?'));
    $reqDeleteAff = $pdo->prepare("DELETE FROM affichages WHERE Id IN ($placeholders)");
    $reqDeleteAff->execute($oldAffIds);
}

$reqOldSon = $pdo->prepare("SELECT Son_Id FROM choregraphie_sons WHERE Choregraphie_Id = :id");
$reqOldSon->bindParam(':id', $modifyId);
$reqOldSon->execute();
$oldSonIds = $reqOldSon->fetchAll(PDO::FETCH_COLUMN);

$reqDeleteLinkSon = $pdo->prepare("DELETE FROM choregraphie_sons WHERE Choregraphie_Id = :id");
$reqDeleteLinkSon->bindParam(':id', $modifyId);
$reqDeleteLinkSon->execute();

if (!empty($oldSonIds)) {
    $placeholders = implode(',', array_fill(0, count($oldSonIds), '?'));
    $reqDeleteSon = $pdo->prepare("DELETE FROM sons WHERE Id IN ($placeholders)");
    $reqDeleteSon->execute($oldSonIds);
}

$req=$pdo->prepare("DELETE FROM choregraphies WHERE id=:id");
$req->bindParam(':id', $modifyId);
$req->execute();

header("Location: ../index.php");