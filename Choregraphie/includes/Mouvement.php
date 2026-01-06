<?php
session_start();

if (!isset($_SESSION['ChoreName'])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choreName'])) {
    $_SESSION['ChoreName'] = filter_input(INPUT_POST, 'choreName', FILTER_SANITIZE_STRING);
    }
    else {
    ?>
    <h1>Nom de la chorégraphie</h1>
    <form method="post">
        <input type="text" name="choreName" placeholder="Nom de votre chorégraphie" required style="width: 250px; height: 40px;">
        <br>
        <br>
        <input type="submit" value="Continuer" class="btn btn-primary">
    </form>
    <?php
    exit();
    }
    }
    ?>
<?php
$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);
$modifier = filter_input(INPUT_GET, "modifier", FILTER_VALIDATE_INT);

$token = rand(0, 1000000);
$_SESSION['tokenMvt'] = $token;

$_SESSION['en_cours_creation'] = true;

if ($modifier == 1 && isset($_SESSION['mouvements'])) {
    $nbMouvements = count($_SESSION['mouvements']);
} else {
    $nbMouvements = isset($_SESSION['nbMouvements']) ? $_SESSION['nbMouvements'] : 1;
}

$mouvements = isset($_SESSION['mouvements']) ? $_SESSION['mouvements'] : [];
?>

<h1>Définir les mouvements</h1>
<form action="Creation.php" method="post">
    <?php for ($i = 0; $i < $nbMouvements; $i++): ?>
        <fieldset style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <legend>Mouvement <?php echo ($i + 1); ?></legend>
            Nom du Mouvement :
            <input type="text" maxlength="50" name="MvtName[]"
                   value="<?php echo isset($mouvements[$i]['nom']) ? htmlspecialchars($mouvements[$i]['nom']) : ''; ?>" required>
            <br>
            Angle de rotation :
            <input type="number" step="0.01" name="MvtAngle[]"
                   value="<?php echo isset($mouvements[$i]['angle']) ? htmlspecialchars($mouvements[$i]['angle']) : ''; ?>" required>
            <br>
            Durée de l'action (secondes) :
            <input type="number" step="0.01" name="MvtTime[]"
                   value="<?php echo isset($mouvements[$i]['time']) ? htmlspecialchars($mouvements[$i]['time']) : ''; ?>" required>
        </fieldset>
    <?php endfor; ?>

    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenMvt" value="<?php echo $token; ?>">

    <button type="submit" name="action" value="ajouter" class="btn btn-primary">Nouveau Mouvement</button>
    <input type="submit" name="action" value="Suivant" class="btn btn-success">
</form>