<?php
session_start();

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);
$modifier = filter_input(INPUT_GET, "modifier", FILTER_VALIDATE_INT);

// Génération d'un token
$token = rand(0, 1000000);
$_SESSION['tokenSon'] = $token;

if ($modifier == 1 && isset($_SESSION['sons'])) {
    $nbSons = count($_SESSION['sons']);
} else {
    $nbSons = isset($_SESSION['nbSons']) ? $_SESSION['nbSons'] : 1;
}

$sons = isset($_SESSION['sons']) ? $_SESSION['sons'] : [];
?>

<h1>Définir les sons</h1>
<form action="Creation.php" method="post">
    <?php for ($i = 0; $i < $nbSons; $i++): ?>
        <fieldset style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <legend>Son <?php echo ($i + 1); ?></legend>
            Nom du son :
            <input type="text" maxlength="50" name="SonName[]"
                   value="<?php echo isset($sons[$i]['nom']) ? htmlspecialchars($sons[$i]['nom']) : ''; ?>" required>
            <br>
            Choisir une note :
            <input type="text" maxlength="10" name="SonNote[]"
                   value="<?php echo isset($sons[$i]['note']) ? htmlspecialchars($sons[$i]['note']) : ''; ?>" required>
            <br>
            Temps (secondes) :
            <input type="number" step="0.01" name="SonTime[]"
                   value="<?php echo isset($sons[$i]['time']) ? htmlspecialchars($sons[$i]['time']) : ''; ?>" required>
        </fieldset>
    <?php endfor; ?>

    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenSon" value="<?php echo $token; ?>">

    <button type="submit" name="action" value="ajouter" class="btn btn-primary">Nouveau Son</button>
    <input type="submit" name="action" value="Suivant" class="btn btn-success">
</form>