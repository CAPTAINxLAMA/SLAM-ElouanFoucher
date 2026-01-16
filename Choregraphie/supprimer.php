<?php
session_start();

include "includes/header.php";

$token=rand(0,1000000);
$_SESSION['token']=$token;

//on récupère l'id
$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT);

//je vais chercher la catégorie à modifier
include_once "includes/config.php";
$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME
    , config::USER, config::PASSWORD);

$req = $pdo->prepare("SELECT Id, ChoreName FROM choregraphies WHERE choregraphies.Id =:id");
$req->bindParam(':id',$id);
$req->execute();
$choregraphies=$req->fetchAll();
if (count($choregraphies)!=1){
    http_response_code(404);
    die("pas de chorégraphie pour l'id ".$id);
}
$choree=$choregraphies[0];
?>
<h1>On supprime cette chorégraphie ???</h1>
<?php
    $choreId = $choree["Id"];

    $reqMvt = $pdo->prepare(" SELECT m.MvtName  FROM choregraphie_mouvements cm JOIN mouvements m ON cm.Mouvement_Id = m.Id WHERE cm.Choregraphie_Id = :id ORDER BY cm.Ordre");
    $reqMvt->bindParam(':id', $choreId);
    $reqMvt->execute();
    $mouvements = $reqMvt->fetchAll();

    $reqAff = $pdo->prepare(" SELECT a.AffName  FROM choregraphie_affichages ca JOIN affichages a ON ca.Affichage_Id = a.Id WHERE ca.Choregraphie_Id = :id ORDER BY ca.Ordre");
    $reqAff->bindParam(':id', $choreId);
    $reqAff->execute();
    $affichages = $reqAff->fetchAll();

    $reqSon = $pdo->prepare("SELECT s.SonNote  FROM choregraphie_sons cs JOIN sons s ON cs.Son_Id = s.Id WHERE cs.Choregraphie_Id = :id ORDER BY cs.Ordre");
    $reqSon->bindParam(':id', $choreId);
    $reqSon->execute();
    $sons = $reqSon->fetchAll();
?>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Nom</th>
        <th>Mouvements</th>
        <th>Affichages</th>
        <th>Sons</th>
    </tr>
    </thead>
    <tr>
        <td><strong><?php echo htmlspecialchars($choree["ChoreName"]); ?></strong></td>
        <td>
            <?php
            if (count($mouvements) > 0) {
                foreach ($mouvements as $mvt) {
                    echo '<span class="badge bg-primary">' . htmlspecialchars($mvt["MvtName"]) . '</span> ';
                }
            } else {
                echo '<em>Aucun</em>';
            }
            ?>
        </td>
        <td>
            <?php
            if (count($affichages) > 0) {
                foreach ($affichages as $aff) {
                    echo '<span class="badge bg-info">' . htmlspecialchars($aff["AffName"]) . '</span> ';
                }
            } else {
                echo '<em>Aucun</em>';
            }
            ?>
        </td>
        <td>
            <?php
            if (count($sons) > 0) {
                foreach ($sons as $son) {
                    echo '<span class="badge bg-success">' . htmlspecialchars($son["SonNote"]) . '</span> ';
                }
            } else {
                echo '<em>Aucun</em>';
            }
            ?>
        </td>
    </tr>
</table>

<form action="actions/delete.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <!--    j'envoie le token dans -->
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <input class='btn btn-danger' type='submit' value="Valider">
    <a href="index.php" class="btn btn-primary">Annuler</a>
</form>

<?php
include "includes/footer.php";
?>
