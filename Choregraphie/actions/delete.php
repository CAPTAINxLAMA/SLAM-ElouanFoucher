<?php
session_start();
$tokenServeur=$_SESSION['token'];
$tokenRecu=filter_input(INPUT_POST,'token',FILTER_VALIDATE_INT);

//je vérifie la cohérence des tokens
if ($tokenRecu != $tokenServeur){
    die("Erreur de token, va mourir vilain hacker.");// je stoppe tout
}
//on récupère les données du POST
$id=filter_input(INPUT_POST,'id',FILTER_VALIDATE_INT);
include "../config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME , config::USER, config::PASSWORD);


//on prépare la requète avec des bindParam pour éviter les injections SQL
$req=$pdo->prepare("delete from choregraphies where id=:id");
$req->bindParam(':id', $id);
$req->execute();

//retour à la page d'accueil
header("Location: ../index.php");