<?php

session_start();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

include_once "includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME, config::USER, config::PASSWORD);

$reqChore = $pdo->prepare("SELECT * FROM choregraphies WHERE Id = :id");
$reqChore->bindParam(':id', $id);
$reqChore->execute();
$choree = $reqChore->fetch();


$_SESSION['modify_id'] = $id;
$_SESSION['ChoreName'] = $choree['ChoreName'];

$reqMvt = $pdo->prepare(" SELECT m.MvtName, m.MvtAngle, m.MvtTime FROM choregraphie_mouvements cm JOIN mouvements m ON cm.Mouvement_Id = m.Id WHERE cm.Choregraphie_Id = :id ORDER BY cm.Ordre");
$reqMvt->bindParam(':id', $id);
$reqMvt->execute();
$mouvements = $reqMvt->fetchAll();

$_SESSION['mouvements'] = [];
foreach ($mouvements as $mvt) {
    $_SESSION['mouvements'][] = [
        'nom' => $mvt['MvtName'],
        'angle' => $mvt['MvtAngle'],
        'time' => $mvt['MvtTime']
    ];
}

$reqAff = $pdo->prepare(" SELECT a.AffName, a.AffText, a.AffTime FROM choregraphie_affichages ca JOIN affichages a ON ca.Affichage_Id = a.Id WHERE ca.Choregraphie_Id = :id ORDER BY ca.Ordre");
$reqAff->bindParam(':id', $id);
$reqAff->execute();
$affichages = $reqAff->fetchAll();

$_SESSION['affichages'] = [];
foreach ($affichages as $aff) {
    $_SESSION['affichages'][] = [
        'nom' => $aff['AffName'],
        'texte' => $aff['AffText'],
        'time' => $aff['AffTime']
    ];
}

$reqSon = $pdo->prepare(" SELECT s.SonNote, s.SonVolume FROM choregraphie_sons cs JOIN sons s ON cs.Son_Id = s.Id WHERE cs.Choregraphie_Id = :id ORDER BY cs.Ordre");
$reqSon->bindParam(':id', $id);
$reqSon->execute();
$sons = $reqSon->fetchAll();

$_SESSION['sons'] = [];
foreach ($sons as $son) {
    $_SESSION['sons'][] = [
        'note' => $son['SonNote'],
        'volume' => $son['SonVolume']
    ];
}

$_SESSION['en_cours_creation'] = true;
$_SESSION['mode_modification'] = true;

header("Location: creer.php?etape=0&modifier=1");
exit();
