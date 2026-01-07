<?php
session_start();

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

include "includes/header.php";
?>

    <h2>Vos créations</h2>

<?php
$success = filter_input(INPUT_GET, "success", FILTER_VALIDATE_INT);
if ($success == 1) {
    echo '<div class="alert alert-success">Chorégraphie créée avec succès !</div>';
}
?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Mouvements</th>
            <th>Affichages</th>
            <th>Sons</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        include_once "includes/config.php";
        $pdo = new PDO("mysql:host=".config::HOST.";dbname=".config::DBNAME, config::USER, config::PASSWORD);

        $req = $pdo->prepare("SELECT Id, ChoreName FROM choregraphies ORDER BY Id DESC");
        $req->execute();
        $chorees = $req->fetchAll();

        foreach ($chorees as $choree)
        {
            $choreId = $choree["Id"];

            $reqMvt = $pdo->prepare(" SELECT m.MvtName  FROM choregraphie_mouvements cm JOIN mouvements m ON cm.Mouvement_Id = m.Id WHERE cm.Choregraphie_Id = :id ORDER BY cm.Ordre");
            $reqMvt->bindParam(':id', $choreId);
            $reqMvt->execute();
            $mouvements = $reqMvt->fetchAll();

            $reqAff = $pdo->prepare(" SELECT a.AffName  FROM choregraphie_affichages ca JOIN affichages a ON ca.Affichage_Id = a.Id WHERE ca.Choregraphie_Id = :id ORDER BY ca.Ordre");
            $reqAff->bindParam(':id', $choreId);
            $reqAff->execute();
            $affichages = $reqAff->fetchAll();

            $reqSon = $pdo->prepare("SELECT s.SonName  FROM choregraphie_sons cs JOIN sons s ON cs.Son_Id = s.Id WHERE cs.Choregraphie_Id = :id ORDER BY cs.Ordre");
            $reqSon->bindParam(':id', $choreId);
            $reqSon->execute();
            $sons = $reqSon->fetchAll();
            ?>


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
                            echo '<span class="badge bg-success">' . htmlspecialchars($son["SonName"]) . '</span> ';
                        }
                    } else {
                        echo '<em>Aucun</em>';
                    }
                    ?>
                </td>
                <td>
                    <a href="tester.php?id=<?php echo $choree["Id"];?>" class="btn btn-primary btn-sm">Tester</a>
                    <a href="modifier.php?id=<?php echo $choree["Id"];?>" class="btn btn-warning btn-sm">Modifier</a>
                    <a href="supprimer.php?id=<?php echo $choree["Id"]?>" class="btn btn-danger btn-sm">Supprimer</a>
                </td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

    <a href="creer.php?etape=0&new=1" class="btn btn-success">Créer une nouvelle chorégraphie</a>

<?php include "includes/footer.php"; ?>