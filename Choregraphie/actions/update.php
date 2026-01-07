<?php
session_start();

$tokenServeur = $_SESSION['token'];
$tokenRecu = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

if ($tokenRecu != $tokenServeur)
{
    die("Erreur de token. Vas mourir vilain hacker");
}

$modifyId = filter_input(INPUT_POST, 'modify_id', FILTER_VALIDATE_INT);


$choreName = isset($_SESSION['ChoreName']) ? $_SESSION['ChoreName'] : 'Chorégraphie modifiée';

$mouvementsJson = filter_input(INPUT_POST, 'mouvements', FILTER_DEFAULT);
$affichagesJson = filter_input(INPUT_POST, 'affichages', FILTER_DEFAULT);
$sonsJson = filter_input(INPUT_POST, 'sons', FILTER_DEFAULT);

$mouvements = json_decode($mouvementsJson, true);
$affichages = json_decode($affichagesJson, true);
$sons = json_decode($sonsJson, true);

include "../includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME, config::USER, config::PASSWORD);

$reqUpdateChore = $pdo->prepare("UPDATE choregraphies SET ChoreName = :choreName WHERE Id = :id");
$reqUpdateChore->bindParam(':choreName', $choreName);
$reqUpdateChore->bindParam(':id', $modifyId);
$reqUpdateChore->execute();

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

if (!empty($mouvements)) {
    foreach ($mouvements as $index => $mvt) {
        $reqMvt = $pdo->prepare("INSERT INTO mouvements (MvtName, MvtAngle, MvtTime) VALUES (:name, :angle, :time)");
        $reqMvt->bindParam(':name', $mvt['nom']);
        $reqMvt->bindParam(':angle', $mvt['angle']);
        $reqMvt->bindParam(':time', $mvt['time']);
        $reqMvt->execute();

        $mouvementId = $pdo->lastInsertId();

        $ordre = $index + 1;
        $reqLinkMvt = $pdo->prepare("INSERT INTO choregraphie_mouvements (Choregraphie_Id, Mouvement_Id, Ordre) VALUES (:chore_id, :mvt_id, :ordre)");
        $reqLinkMvt->bindParam(':chore_id', $modifyId);
        $reqLinkMvt->bindParam(':mvt_id', $mouvementId);
        $reqLinkMvt->bindParam(':ordre', $ordre);
        $reqLinkMvt->execute();
    }
}

if (!empty($affichages)) {
    foreach ($affichages as $index => $aff) {
        $reqAff = $pdo->prepare("INSERT INTO affichages (AffName, AffText, AffTime) VALUES (:name, :text, :time)");
        $reqAff->bindParam(':name', $aff['nom']);
        $reqAff->bindParam(':text', $aff['texte']);
        $reqAff->bindParam(':time', $aff['time']);
        $reqAff->execute();

        $affichageId = $pdo->lastInsertId();

        $ordre = $index + 1;
        $reqLinkAff = $pdo->prepare("INSERT INTO choregraphie_affichages (Choregraphie_Id, Affichage_Id, Ordre) VALUES (:chore_id, :aff_id, :ordre)");
        $reqLinkAff->bindParam(':chore_id', $modifyId);
        $reqLinkAff->bindParam(':aff_id', $affichageId);
        $reqLinkAff->bindParam(':ordre', $ordre);
        $reqLinkAff->execute();
    }
}

if (!empty($sons)) {
    foreach ($sons as $index => $son) {
        $reqSon = $pdo->prepare("INSERT INTO sons (SonName, SonNote, SonTime) VALUES (:name, :note, :time)");
        $reqSon->bindParam(':name', $son['nom']);
        $reqSon->bindParam(':note', $son['note']);
        $reqSon->bindParam(':time', $son['time']);
        $reqSon->execute();

        $sonId = $pdo->lastInsertId();

        $ordre = $index + 1;
        $reqLinkSon = $pdo->prepare("INSERT INTO choregraphie_sons (Choregraphie_Id, Son_Id, Ordre) VALUES (:chore_id, :son_id, :ordre)");
        $reqLinkSon->bindParam(':chore_id', $modifyId);
        $reqLinkSon->bindParam(':son_id', $sonId);
        $reqLinkSon->bindParam(':ordre', $ordre);
        $reqLinkSon->execute();
    }
}

unset($_SESSION['mouvements']);
unset($_SESSION['nbMouvements']);
unset($_SESSION['affichages']);
unset($_SESSION['nbAffichages']);
unset($_SESSION['sons']);
unset($_SESSION['nbSons']);
unset($_SESSION['en_cours_creation']);
unset($_SESSION['ChoreName']);
unset($_SESSION['mode_modification']);
unset($_SESSION['modify_id']);

header("Location: ../index.php?updated=1");
exit();
?>