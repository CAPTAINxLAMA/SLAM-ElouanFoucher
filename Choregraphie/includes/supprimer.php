<?php
session_start();

include "../header.php";

$token=rand(0,1000000);
$_SESSION['token']=$token;

//on récupère l'id
$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);

//je vais chercher la catégorie à modifier
include_once "../config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME
    , config::USER, config::PASSWORD);

$req=$pdo->prepare('SELECT choregraphies.Id, mouvements.MvtName, affichages.AffName, sons.SonName FROM choregraphies LEFT JOIN mouvements ON choregraphies.Mouvement_Id = mouvements.Id LEFT JOIN affichages ON choregraphies.Affichage_Id = affichages.Id LEFT JOIN sons ON choregraphies.Son_Id = sons.Id WHERE choregraphies.id=:id');
$req->bindParam(':id',$id);
$req->execute();
$choregraphies=$req->fetchAll();
//je vérifie que j'en ai bien récupéré qu'une seule
if (count($choregraphies)!=1){
    //on renvoie une erreur 404
    http_response_code(404);
    die("pas de chorégraphie pour l'id ".$id);
}
$choree=$choregraphies[0];//je récupère la catégorie à modifier
?>
<h1>On supprime cette chorégraphie ???</h1>

<tr>
    <td>Numéro : <?php echo  $choree["Id"] ?></td>
    <br>
    <td>Mouvement : <?php echo $choree["MvtName"] ?></td>
    <br>
    <td>Affichage : <?php echo $choree["AffName"] ?></td>
    <br>
    <td>Son : <?php echo $choree["SonName"] ?></td>
    <br>
    <td>

<form action="../actions/delete.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <!--    j'envoie le token dans -->
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <input class='btn btn-danger'type='submit' value="Valider">
    <a href="../index.php" class="btn btn-primary">Annuler</a>
</form>

<?php
include "../footer.php";
?>
