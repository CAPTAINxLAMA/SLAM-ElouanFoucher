<?php

// Génération d'un token
session_start();
$token = rand(0, 1000000);
$_SESSION['tokenAff'] = $token;

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

?>

<h1>Définir un affichage</h1>
<form action="Creation.php" method="post">
    Texte à afficher <input type="text" maxlength="50" name="AffText">
    <br>
    Temps d'apparition (secondes) <input type="number" name="AffTime">
    <br>
    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenAff" value="<?php echo $token; ?>">
    <input type="submit" value="Suivant" class="btn btn-success">
</form>