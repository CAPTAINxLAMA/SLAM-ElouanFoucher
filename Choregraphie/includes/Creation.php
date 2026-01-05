<?php
session_start();

$etape = filter_input(INPUT_POST, 'etape', FILTER_DEFAULT);
$action = filter_input(INPUT_POST, 'action', FILTER_DEFAULT);

// ========================
// ÉTAPE 0 : MOUVEMENTS
// ========================
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
        // Récupérer les tableaux de données
        $names = $_POST['MvtName'];
        $angles = $_POST['MvtAngle'];
        $times = $_POST['MvtTime'];

        if ($action == "ajouter")
        {
            // Sauvegarder temporairement les mouvements
            $mouvements = [];
            foreach ($names as $key => $name) {
                $mouvements[] = [
                        'nom' => $name,
                        'angle' => $angles[$key],
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
            foreach ($names as $key => $name) {
                $mouvements[] = [
                        'nom' => $name,
                        'angle' => $angles[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['mouvements'] = $mouvements;

            // Réinitialiser le compteur
            unset($_SESSION['nbMouvements']);

            if (isset($_SESSION['affichages']) && count($_SESSION['affichages']) > 0) {
                header("Location: creer.php?etape=1&modifier=1");
            } else {
                header("Location: creer.php?etape=1");
            }
        }
    }
}

// ========================
// ÉTAPE 1 : AFFICHAGES
// ========================
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
        // Récupérer les tableaux de données
        $names = $_POST['AffName'];
        $texts = $_POST['AffText'];
        $times = $_POST['AffTime'];

        if ($action == "ajouter")
        {
            // Sauvegarder temporairement les affichages
            $affichages = [];
            foreach ($names as $key => $name) {
                $affichages[] = [
                        'nom' => $name,
                        'texte' => $texts[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['affichages'] = $affichages;
            $_SESSION['nbAffichages'] = count($affichages) + 1;

            // Recharger le formulaire avec un affichage supplémentaire
            header("Location: creer.php?etape=1");
        }
        else if ($action == "Suivant")
        {
            // Sauvegarder tous les affichages en session
            $affichages = [];
            foreach ($names as $key => $name) {
                $affichages[] = [
                        'nom' => $name,
                        'texte' => $texts[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['affichages'] = $affichages;

            // Réinitialiser le compteur
            unset($_SESSION['nbAffichages']);

            if (isset($_SESSION['sons']) && count($_SESSION['sons']) > 0) {
                header("Location: creer.php?etape=2&modifier=1");
            } else {
                header("Location: creer.php?etape=2");
            }
        }
    }
}

// ========================
// ÉTAPE 2 : SONS
// ========================
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
        // Récupérer les tableaux de données
        $names = $_POST['SonName'];
        $notes = $_POST['SonNote'];
        $times = $_POST['SonTime'];

        if ($action == "ajouter")
        {
            // Sauvegarder temporairement les sons
            $sons = [];
            foreach ($names as $key => $name) {
                $sons[] = [
                        'nom' => $name,
                        'note' => $notes[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['sons'] = $sons;
            $_SESSION['nbSons'] = count($sons) + 1;

            // Recharger le formulaire avec un son supplémentaire
            header("Location: creer.php?etape=2");
        }
        else if ($action == "Suivant")
        {
            // Sauvegarder tous les sons en session
            $sons = [];
            foreach ($names as $key => $name) {
                $sons[] = [
                        'nom' => $name,
                        'note' => $notes[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['sons'] = $sons;

            // Réinitialiser le compteur
            unset($_SESSION['nbSons']);

            $etape = 3;
        }
    }
}

// ========================
// ÉTAPE 3 : RÉCAPITULATIF
// ========================
if ($etape == "3")
{
    include "../header.php";
    $token = rand(0, 1000000);
    $_SESSION['token'] = $token;
    ?>

    <h1>Récapitulatif de votre chorégraphie</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Étape</th>
            <th>Nom</th>
            <th>Contenu</th>
            <th>Durée (s)</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Afficher les mouvements
        if (isset($_SESSION['mouvements'])):
            $mouvements = $_SESSION['mouvements'];
            foreach ($mouvements as $index => $mvt):
                ?>
                <tr>
                    <td>Mouvement <?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($mvt['nom']); ?></td>
                    <td>Angle : <?php echo htmlspecialchars($mvt['angle']); ?>°</td>
                    <td><?php echo htmlspecialchars($mvt['time']); ?></td>
                    <td>
                        <a href="creer.php?etape=0&modifier=1" class="btn btn-warning btn-sm">Modifier</a>
                    </td>
                </tr>
            <?php
            endforeach;
        endif;
        ?>

        <?php
        // Afficher les affichages
        if (isset($_SESSION['affichages'])):
            $affichages = $_SESSION['affichages'];
            foreach ($affichages as $index => $aff):
                ?>
                <tr>
                    <td>Affichage <?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($aff['nom']); ?></td>
                    <td>Texte : <?php echo htmlspecialchars($aff['texte']); ?></td>
                    <td><?php echo htmlspecialchars($aff['time']); ?></td>
                    <td>
                        <a href="creer.php?etape=1&modifier=1" class="btn btn-warning btn-sm">Modifier</a>
                    </td>
                </tr>
            <?php
            endforeach;
        endif;
        ?>

        <?php
        // Afficher les sons
        if (isset($_SESSION['sons'])):
            $sons = $_SESSION['sons'];
            foreach ($sons as $index => $son):
                ?>
                <tr>
                    <td>Son <?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($son['nom']); ?></td>
                    <td>Note : <?php echo htmlspecialchars($son['note']); ?></td>
                    <td><?php echo htmlspecialchars($son['time']); ?></td>
                    <td>
                        <a href="creer.php?etape=2&modifier=1" class="btn btn-warning btn-sm">Modifier</a>
                    </td>
                </tr>
            <?php
            endforeach;
        endif;
        ?>
        </tbody>
    </table>

    <form action="../actions/create.php" method="post">
        <input type="hidden" name="mouvements" value='<?php echo json_encode($_SESSION['mouvements']); ?>'>
        <input type="hidden" name="affichages" value='<?php echo json_encode($_SESSION['affichages']); ?>'>
        <input type="hidden" name="sons" value='<?php echo json_encode($_SESSION['sons']); ?>'>
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" value="Créer la chorégraphie" class="btn btn-success">
    </form>
    <br>
    <a href="../index.php?cancel=1" class="btn btn-secondary">Annuler</a>

    <?php
    include "../footer.php";
}
?>