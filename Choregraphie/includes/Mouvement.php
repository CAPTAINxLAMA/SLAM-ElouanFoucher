<?php

// Génération d'un token
session_start();
$token = rand(0, 1000000);
$_SESSION['tokenMvt'] = $token;

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

?>

<h1>Définir un mouvement</h1>
<form action="Creation.php" method="post">
    Angle de rotation <input type=number step=0.01 maxlength="20" name="MvtAngle">
    <br>
    Durée de l'action (secondes) <input type="number" name="MvtTime">
    <br>
    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenMvt" value="<?php echo $token; ?>">
    <input type="submit" value="Suivant" class="btn btn-success">
</form>