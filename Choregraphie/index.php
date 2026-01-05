<?php
session_start();

// Nettoyer la session si annulation ou nouvelle création
$cancel = filter_input(INPUT_GET, "cancel", FILTER_VALIDATE_INT);
$new = filter_input(INPUT_GET, "new", FILTER_VALIDATE_INT);

if ($cancel == 1 || $new == 1) {
    unset($_SESSION['mouvements']);
    unset($_SESSION['nbMouvements']);
    unset($_SESSION['affichages']);
    unset($_SESSION['nbAffichages']);
    unset($_SESSION['sons']);
    unset($_SESSION['nbSons']);
    unset($_SESSION['en_cours_creation']);
    unset($_SESSION['ChoreName']);
}

include "header.php"; ?>

    <h2>Vos créations</h2>

    <table class="table table-stripped">
        <tr>
            <th>Numéro</th>
            <th>Mouvement</th>
            <th>Affichage</th>
            <th>Son</th>
            <th></th>
        </tr>
        <?php
        include_once "config.php";
        $pdo = new PDO("mysql:host=".config::HOST.";dbname=".config::DBNAME, config::USER, config::PASSWORD);

        $req = $pdo->prepare("SELECT choregraphies.Id, mouvements.MvtName, affichages.AffName, sons.SonName FROM choregraphies LEFT JOIN mouvements ON choregraphies.Mouvement_Id = mouvements.Id LEFT JOIN affichages ON choregraphies.Affichage_Id = affichages.Id LEFT JOIN sons ON choregraphies.Son_Id = sons.Id");
        $req->execute();
        $chorees = $req->fetchAll();

        foreach ($chorees as $choree)
        {
            ?>
            <tr>
                <td><?php echo $choree["Id"] ?></td>
                <td><?php echo $choree["MvtName"] ?></td>
                <td><?php echo $choree["AffName"] ?></td>
                <td><?php echo $choree["SonName"] ?></td>
                <td>
                    <a href="includes/tester.php?id=<?php echo $choree["Id"] ?>" class="btn btn-primary">Tester</a>
                    <a href="includes/modifier.php?id=<?php echo $choree["Id"] ?>" class="btn btn-warning">Modifier</a>
                    <a href="includes/supprimer.php?id=<?php echo $choree["Id"] ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <a href="includes/creer.php?etape=0&new=1" class="btn btn-success">Créer</a>

<?php include "footer.php"; ?>