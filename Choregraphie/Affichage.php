<?php
session_start();

$supprimer = filter_input(INPUT_GET, "supprimer", FILTER_VALIDATE_INT);
if ($supprimer !== null && $supprimer !== false && isset($_SESSION['affichages'])) {
    if (count($_SESSION['affichages']) >= 1 && isset($_SESSION['affichages'][$supprimer])) {
        unset($_SESSION['affichages'][$supprimer]);
        $_SESSION['affichages'] = array_values($_SESSION['affichages']);
    }
    header("Location: creer.php?etape=1&modifier=1");
    exit();
}

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);
$modifier = filter_input(INPUT_GET, "modifier", FILTER_VALIDATE_INT);

$token = rand(0, 1000000);
$_SESSION['tokenAff'] = $token;

if ($modifier == 1 && isset($_SESSION['affichages'])) {
    $nbAffichages = count($_SESSION['affichages']);
} else {
    $nbAffichages = isset($_SESSION['nbAffichages']) ? $_SESSION['nbAffichages'] : 1;
}

$affichages = isset($_SESSION['affichages']) ? $_SESSION['affichages'] : [];
?>

<h1>Définir les affichages</h1>
<form action="Creation.php" method="post">
    <?php for ($i = 0; $i < $nbAffichages; $i++): ?>
        <fieldset style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <legend>Affichage <?php echo ($i + 1); ?>
                <?php if ($nbAffichages >= 1): ?>
                <button type="submit" name="action_supprimer" value="<?php echo $i; ?>" class="btn btn-danger btn-sm btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer cet affichage ?')" formnovalidate>✕ Supprimer</button>
                <?php endif; ?>
            </legend>
            Nom de l'affichage :
            <input type="text" maxlength="50" name="AffName[]"
                   value="<?php echo isset($affichages[$i]['nom']) ? htmlspecialchars($affichages[$i]['nom']) : ''; ?>" required>
            <br>
            Texte à afficher :
            <input type="text" maxlength="50" name="AffText[]"
                   value="<?php echo isset($affichages[$i]['texte']) ? htmlspecialchars($affichages[$i]['texte']) : ''; ?>" required>
            <br>
            Temps d'apparition (secondes) :
            <input type="number" step="0.01" name="AffTime[]"
                   value="<?php echo isset($affichages[$i]['time']) ? htmlspecialchars($affichages[$i]['time']) : ''; ?>" required>
        </fieldset>
    <?php endfor; ?>

    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenAff" value="<?php echo $token; ?>">

    <button type="submit" name="action" value="ajouter" class="btn btn-primary">Nouvel Affichage</button>
    <input type="submit" name="action" value="Suivant" class="btn btn-success">
</form>