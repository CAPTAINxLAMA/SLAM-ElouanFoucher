<?php

session_start();

include "includes/header.php";

$token=rand(0,1000000);
$_SESSION['token']=$token;

$id=filter_input(INPUT_GET,"id",FILTER_VALIDATE_INT)

?>
<h1>Envoyer la notification à l'ESP32 ???</h1>
<form action="actions/MessageMosquitto.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <input class='btn btn-danger' type='submit' value="Valider">
    <a href="index.php" class="btn btn-primary">Annuler</a>
</form>
<?php
include 'includes/footer.php'
?>

