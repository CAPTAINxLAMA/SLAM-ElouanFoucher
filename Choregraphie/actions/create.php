<?php
session_start();

// Vérification du token
$tokenServeur = $_SESSION['token'];
$tokenRecu = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

if ($tokenRecu != $tokenServeur)
{
    die("Erreur de token. Vas mourir vilain hacker");
}

$choreName = isset($_SESSION['ChoreName']) ? $_SESSION['ChoreName'] : 'Nouvelle Chorégraphie';

$mouvementsJson = filter_input(INPUT_POST, 'mouvements', FILTER_DEFAULT);
$affichagesJson = filter_input(INPUT_POST, 'affichages', FILTER_DEFAULT);
$sonsJson = filter_input(INPUT_POST, 'sons', FILTER_DEFAULT);

$mouvements = json_decode($mouvementsJson, true);
$affichages = json_decode($affichagesJson, true);
$sons = json_decode($sonsJson, true);

include "../includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME, config::USER, config::PASSWORD);

$reqChore = $pdo->prepare("INSERT INTO choregraphies (ChoreName) VALUES (:choreName)");
$reqChore->bindParam(':choreName', $choreName);
$reqChore->execute();

$choregraphieId = $pdo->lastInsertId();

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
        $reqLinkMvt->bindParam(':chore_id', $choregraphieId);
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
        $reqLinkAff->bindParam(':chore_id', $choregraphieId);
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
        $reqLinkSon->bindParam(':chore_id', $choregraphieId);
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

header("Location: ../index.php?success=1");
exit();
?>