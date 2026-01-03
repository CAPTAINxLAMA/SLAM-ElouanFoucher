<?php
session_start();

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

// Génération d'un token
$token = rand(0, 1000000);
$_SESSION['tokenAff'] = $token;

$nbAffichages = isset($_SESSION['nbAffichages']) ? $_SESSION['nbAffichages'] : 1;
$affichages = isset($_SESSION['affichages']) ? $_SESSION['affichages'] : [];
?>

<h1>Définir les affichages</h1>
<form action="Creation.php" method="post">
    <?php for ($i = 0; $i < $nbAffichages; $i++): ?>
        <fieldset style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <legend>Affichage <?php echo ($i + 1); ?></legend>
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