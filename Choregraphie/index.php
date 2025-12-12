<?php include "header.php"; ?>

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

        $req = $pdo->prepare("SELECT choregraphies.Id, mouvements.Nom NameMvm, affichages.Nom NameAff, sons.Nom NameSon FROM choregraphies LEFT JOIN mouvements ON choregraphies.Mouvement_Id = mouvements.Id LEFT JOIN affichages ON choregraphies.Affichage_Id = affichages.Id LEFT JOIN sons ON choregraphies.Son_Id = sons.Id");
        $req->execute();
        $chorees = $req->fetchAll();

        foreach ($chorees as $choree)
        {
            ?>
            <tr>
                <td><?php echo $choree["Id"] ?></td>
                <td><?php echo $choree["NameMvm"] ?></td>
                <td><?php echo $choree["NameAff"] ?></td>
                <td><?php echo $choree["NameSon"] ?></td>
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
    <a href="includes/creer.php?etape=0" class="btn btn-success">Créer</a>

<?php include "footer.php"; ?>