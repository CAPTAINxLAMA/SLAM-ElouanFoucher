<?php

// Génération d'un token
session_start();
$token = rand(0, 1000000);
$_SESSION['tokenSon'] = $token;

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

?>

<h1>Définir une sonorité</h1>
<form action="Creation.php" method="post">
    Choisir une note <input type="text" maxlength="50" name="SonNote">
    <br>
    Temps (secondes) <input type="number" name="SonTime">
    <br>
    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenSon" value="<?php echo $token; ?>">
    <input type="submit" value="Suivant" class="btn btn-success">
</form>