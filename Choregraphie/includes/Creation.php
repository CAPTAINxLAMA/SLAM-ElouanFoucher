<?php
session_start();

$etape = filter_input(INPUT_POST, 'etape', FILTER_DEFAULT);
$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);

if ($etape == "0")
{
    $tokenServeur = $_SESSION['tokenMvt'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenMvt', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        // Récupérer les tableaux d'angles et de temps
        $angles = $_POST['MvtAngle'];
        $times = $_POST['MvtTime'];

        if ($action == "ajouter")
        {
            // Sauvegarder temporairement les mouvements
            $mouvements = [];
            foreach ($angles as $key => $angle) {
                $mouvements[] = [
                        'angle' => $angle,
                        'time' => $times[$key]
                ];
            }
            $_SESSION['mouvements'] = $mouvements;
            $_SESSION['nbMouvements'] = count($mouvements) + 1;

            // Recharger le formulaire avec un mouvement supplémentaire
            header("Location: creer.php?etape=0");
        }
        else if ($action == "Suivant")
        {
            // Sauvegarder tous les mouvements en session
            $mouvements = [];
            foreach ($angles as $key => $angle) {
                $mouvements[] = [
                        'angle' => $angle,
                        'time' => $times[$key]
                ];
            }
            $_SESSION['mouvements'] = $mouvements;

            // Réinitialiser le compteur
            unset($_SESSION['nbMouvements']);

            header("Location: creer.php?etape=1");
        }
    }
}

else if ($etape == "1")
{
    $tokenServeur = $_SESSION['tokenAff'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenAff', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        $_SESSION['AffText'] = filter_input(INPUT_POST, 'AffText', FILTER_DEFAULT);
        $_SESSION['AffTime'] = filter_input(INPUT_POST, 'AffTime', FILTER_DEFAULT);
        header("Location: creer.php?etape=2");
    }
}

else if ($etape == "2")
{
    $tokenServeur = $_SESSION['tokenSon'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenSon', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        $_SESSION['SonNote'] = filter_input(INPUT_POST, 'SonNote', FILTER_DEFAULT);
        $_SESSION['SonTime'] = filter_input(INPUT_POST, 'SonTime', FILTER_DEFAULT);
        $etape = 3;
    }
}

if ($etape == "3")
{
    include "../header.php";
    $token = rand(0, 1000000);
    $_SESSION['token'] = $token;
    ?>

    <h1><?php echo htmlspecialchars($_SESSION['ChoreName']);?></h1>
    <table class="table table-stripped">
        <tr>
            <th>Étape</th>
            <th>Nom</th>
            <th>Contenu</th>
            <th>Durée (en seconde)</th>
            <th></th>
        </tr>
        <?php
//        $mouvements = $_SESSION['mouvements'];
//        foreach ($mouvements as $index => $mvt):
//            ?>
<!--            <tr>-->
<!--                <td>--><?php //echo htmlspecialchars($mvt['angle']); ?><!--°</td>-->
<!--                <td>--><?php //echo htmlspecialchars($mvt['time']); ?><!--s</td>-->
<!--                <td>Mouvement --><?php //echo ($index + 1); ?><!--</td>-->
<!--            </tr>-->
<!--        --><?php //endforeach; ?>
        <?php
        $mouvements = $_SESSION['mouvements'];
        foreach ($mouvements as $index => $mvt):
        ?>
        <tr>
            <td>Mouvement <?php echo ($index + 1); ?></td>
            <td><?php echo isset($mvt['nom']) ? htmlspecialchars($mvt['nom']) : '-'; ?></td>
            <td>Angle : <?php echo htmlspecialchars($mvt['angle']); ?>°</td>
            <td><?php echo htmlspecialchars($mvt['time']); ?></td>
            <td>
                <?php if ($index == 0): ?>
                    <a href="Mouvement.php?etape=0" class="btn btn-warning btn-sm">Modifier</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><a href="creer.php?etape=0" class="btn btn-warning">Modifier les mouvements</a></td>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($_SESSION['AffText']); ?></td>
            <td><?php echo htmlspecialchars($_SESSION['AffTime']); ?></td>
            <td><a href="creer.php?etape=1" class="btn btn-warning">Modifier</a></td>
        </tr>
        <tr>
            <td><?php echo htmlspecialchars($_SESSION['SonNote']); ?></td>
            <td><?php echo htmlspecialchars($_SESSION['SonTime']); ?></td>
            <td><a href="creer.php?etape=2" class="btn btn-warning">Modifier</a></td>
        </tr>
    </table>

    <form action="../actions/create.php" method="post">
        <input type="hidden" name="mouvements" value='<?php echo json_encode($_SESSION['mouvements']); ?>'>
        <input type="hidden" name="AffText" value="<?php echo htmlspecialchars($_SESSION['AffText']); ?>">
        <input type="hidden" name="AffTime" value="<?php echo htmlspecialchars($_SESSION['AffTime']); ?>">
        <input type="hidden" name="SonNote" value="<?php echo htmlspecialchars($_SESSION['SonNote']); ?>">
        <input type="hidden" name="SonTime" value="<?php echo htmlspecialchars($_SESSION['SonTime']); ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" value="Créer" class="btn btn-success">
    </form>

    <?php
    include "../footer.php";
}