<?php

session_start();
$tokenServeur = $_SESSION['token'];
$tokenRecu = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

if ($tokenRecu != $tokenServeur)
{
    die("Erreur de token. Vas mourir vilain hacker");
}
$AffName=filter_input(INPUT_POST,'AffName',FILTER_DEFAULT);
$AffText=filter_input(INPUT_POST,'AffText',FILTER_DEFAULT);
$AffTime=filter_input(INPUT_POST,'AffTime',FILTER_DEFAULT);
$MvtName=filter_input(INPUT_POST,'MvtName',FILTER_DEFAULT);
$MvtAngle=filter_input(INPUT_POST,'MvtAngle',FILTER_DEFAULT);
$MvtTime=filter_input(INPUT_POST,'MvtTime',FILTER_DEFAULT);
$SonName=filter_input(INPUT_POST,'SonName',FILTER_DEFAULT);
$SonNote=filter_input(INPUT_POST,'SonNote',FILTER_DEFAULT);
$SonTime=filter_input(INPUT_POST,'SonTime',FILTER_DEFAULT);

include "../config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME , config::USER, config::PASSWORD);


//on prépare la requète avec des bindParam pour éviter les injections SQL
$req=$pdo->prepare("INSERT INTO  affichages VALUES (:AffName, :AffText, :AffTime)");
$req=$pdo->prepare("INSERT INTO  mouvements VALUES (:MvtName, :MvtAngle, :MvtTime)");
$req=$pdo->prepare("INSERT INTO  sons VALUES (:SonName, :SonNote, :SonTime)");

$req->bindParam(':AffName', $AffName);
$req->bindParam(':AffText', $AffText);
$req->bindParam(':AffTime', $AffTime);
$req->bindParam(':MvtName', $MvtName);
$req->bindParam(':MvtAngle', $MvtAngle);
$req->bindParam(':MvtTime', $MvtTime);
$req->bindParam(':SonName', $SonName);
$req->bindParam(':SonNote', $SonNote);
$req->bindParam(':SonTime', $SonTime);


$req->execute();

unset($_SESSION['mouvements']);
unset($_SESSION['nbMouvements']);
unset($_SESSION['affichages']);
unset($_SESSION['nbAffichages']);
unset($_SESSION['sons']);
unset($_SESSION['nbSons']);
unset($_SESSION['en_cours_creation']);

//retour à la page d'accueil
header("Location: ../index.php");