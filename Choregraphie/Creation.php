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
        $names = isset($_POST['MvtName']) ? $_POST['MvtName'] : [];
        $angles = isset($_POST['MvtAngle']) ? $_POST['MvtAngle'] : [];
        $times = isset($_POST['MvtTime']) ? $_POST['MvtTime'] : [];

        $actionSupprimer = filter_input(INPUT_POST, 'action_supprimer', FILTER_VALIDATE_INT);
        if ($actionSupprimer !== null && $actionSupprimer !== false) {
            $mouvements = [];
            foreach ($names as $key => $name) {
                $mouvements[] = [
                        'nom' => $name,
                        'angle' => $angles[$key],
                        'time' => $times[$key]
                ];
            }

            if (count($mouvements) >= 1 && isset($mouvements[$actionSupprimer])) {
                unset($mouvements[$actionSupprimer]);
                $mouvements = array_values($mouvements);
            }

            $_SESSION['mouvements'] = $mouvements;

            header("Location: creer.php?etape=0&modifier=1");
            exit();
        }

        if ($action == "ajouter")
        {
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

            header("Location: creer.php?etape=0");
        }
        else if ($action == "Suivant")
        {
            $mouvements = [];
            foreach ($names as $key => $name) {
                $mouvements[] = [
                        'nom' => $name,
                        'angle' => $angles[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['mouvements'] = $mouvements;

            unset($_SESSION['nbMouvements']);

            if (isset($_SESSION['affichages']) && count($_SESSION['affichages']) > 0) {
                header("Location: creer.php?etape=1&modifier=1");
            } else {
                header("Location: creer.php?etape=1");
            }
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
        $names = isset($_POST['AffName']) ? $_POST['AffName'] : [];
        $texts = isset($_POST['AffText']) ? $_POST['AffText'] : [];
        $times = isset($_POST['AffTime']) ? $_POST['AffTime'] : [];

        $actionSupprimer = filter_input(INPUT_POST, 'action_supprimer', FILTER_VALIDATE_INT);
        if ($actionSupprimer !== null && $actionSupprimer !== false) {
            $affichages = [];
            foreach ($names as $key => $name) {
                $affichages[] = [
                        'nom' => $name,
                        'texte' => $texts[$key],
                        'time' => $times[$key]
                ];
            }

            if (count($affichages) >= 1 && isset($affichages[$actionSupprimer])) {
                unset($affichages[$actionSupprimer]);
                $affichages = array_values($affichages);
            }

            $_SESSION['affichages'] = $affichages;

            header("Location: creer.php?etape=1&modifier=1");
            exit();
        }

        if ($action == "ajouter")
        {
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

            header("Location: creer.php?etape=1");
        }
        else if ($action == "Suivant")
        {
            $affichages = [];
            foreach ($names as $key => $name) {
                $affichages[] = [
                        'nom' => $name,
                        'texte' => $texts[$key],
                        'time' => $times[$key]
                ];
            }
            $_SESSION['affichages'] = $affichages;

            unset($_SESSION['nbAffichages']);

            if (isset($_SESSION['sons']) && count($_SESSION['sons']) > 0) {
                header("Location: creer.php?etape=2&modifier=1");
            } else {
                header("Location: creer.php?etape=2");
            }
        }
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
        $notes = isset($_POST['SonNote']) ? $_POST['SonNote'] : [];
        $volumes = isset($_POST['SonVolume']) ? $_POST['SonVolume'] : [];

        $actionSupprimer = filter_input(INPUT_POST, 'action_supprimer', FILTER_VALIDATE_INT);
        if ($actionSupprimer !== null && $actionSupprimer !== false) {
            $sons = [];
            foreach ($notes as $key => $note) {
                $sons[] = [
                        'note' => $note,
                        'volume' => $volumes[$key]
                ];
            }

            if (count($sons) >= 1 && isset($sons[$actionSupprimer])) {
                unset($sons[$actionSupprimer]);
                $sons = array_values($sons);
            }

            $_SESSION['sons'] = $sons;

            header("Location: creer.php?etape=2&modifier=1");
            exit();
        }

        if ($action == "ajouter")
        {
            $sons = [];
            foreach ($notes as $key => $note) {
                $sons[] = [
                        'note' => $note,
                        'volume' => $volumes[$key]
                ];
            }
            $_SESSION['sons'] = $sons;
            $_SESSION['nbSons'] = count($sons) + 1;

            header("Location: creer.php?etape=2");
        }
        else if ($action == "Suivant")
        {
            $sons = [];
            foreach ($notes as $key => $note) {
                $sons[] = [
                        'note' => $note,
                        'volume' => $volumes[$key]
                ];
            }
            $_SESSION['sons'] = $sons;

            unset($_SESSION['nbSons']);

            $etape = 3;
        }
    }
}

if ($etape == "3")
{
    include "includes/header.php";
    $token = rand(0, 1000000);
    $_SESSION['token'] = $token;

    // différence entre modif après la création ou pendant la création
    $modeModification = isset($_SESSION['mode_modification']) && $_SESSION['mode_modification'] === true && isset($_SESSION['modify_id']);
    $actionUrl = $modeModification ? "actions/update.php" : "actions/create.php";
    $boutonTexte = $modeModification ? "Mettre à jour" : "Créer la chorégraphie";
    ?>

    <h1>Récapitulatif de votre chorégraphie</h1>

    <?php if ($modeModification): ?>
    <div class="alert alert-info">
        <strong>Mode modification</strong> - Vous modifiez une chorégraphie existante.
    </div>
<?php endif; ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Étape</th>
            <th>Nom</th>
            <th>Contenu</th>
            <th>Durée (s) / Volume</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (isset($_SESSION['mouvements']) && !empty($_SESSION['mouvements'])):
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
        else:
            ?>
            <tr>
                <td colspan="5" class="text-center">
                    <em>Aucun mouvement</em> -
                    <a href="creer.php?etape=0" class="btn btn-primary">Ajouter des mouvements</a>
                </td>
            </tr>
        <?php
        endif;
        ?>

        <?php
        if (isset($_SESSION['affichages']) && !empty($_SESSION['affichages'])):
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
        else:
            ?>
            <tr>
                <td colspan="5" class="text-center">
                    <em>Aucun affichage</em> -
                    <a href="creer.php?etape=1" class="btn btn-primary">Ajouter des affichages</a>
                </td>
            </tr>
        <?php
        endif;
        ?>

        <?php
        if (isset($_SESSION['sons']) && !empty($_SESSION['sons'])):
            $sons = $_SESSION['sons'];
            foreach ($sons as $index => $son):
                ?>
                <tr>
                    <td>Son <?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars(str_replace('.mp3', '', $son['note'])); ?></td>
                    <td>Fichier : <?php echo htmlspecialchars($son['note']); ?></td>
                    <td><?php echo htmlspecialchars($son['volume']); ?>%</td>
                    <td>
                        <a href="creer.php?etape=2&modifier=1" class="btn btn-warning btn-sm">Modifier</a>
                    </td>
                </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="5" class="text-center">
                    <em>Aucun son</em> -
                    <a href="creer.php?etape=2" class="btn btn-primary">Ajouter des sons</a>
                </td>
            </tr>
        <?php
        endif;
        ?>
        </tbody>
    </table>

    <form action="<?php echo $actionUrl; ?>" method="post">
        <input type="hidden" name="mouvements" value="<?php echo htmlspecialchars(json_encode($_SESSION['mouvements'] ?? []), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="affichages" value="<?php echo htmlspecialchars(json_encode($_SESSION['affichages'] ?? []), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="sons" value="<?php echo htmlspecialchars(json_encode($_SESSION['sons'] ?? []), ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <?php if ($modeModification): ?>
            <input type="hidden" name="modify_id" value="<?php echo $_SESSION['modify_id']; ?>">
        <?php endif; ?>
        <input type="submit" value="<?php echo $boutonTexte; ?>" class="btn btn-success">
    </form>
    <br>
    <a href="index.php?cancel=1" class="btn btn-secondary">Annuler</a>

    <?php
    include "includes/footer.php";
}