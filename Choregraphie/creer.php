<?php include "includes/header.php";

// Récupération de l'avancement de l'étape
$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

if ($etape === 0)
{
    include "Mouvement.php";
    ?>
    <br>
    <a href="index.php?cancel=1" class="btn btn-secondary">Annuler</a>
    <?php
}

else if ($etape === 1)
{
    include "Affichage.php";
    ?>
    <br>
    <a href="index.php?cancel=1" class="btn btn-secondary">Annuler</a>
    <?php
}

else if ($etape === 2)
{
    include "Son.php";
    ?>
    <br>
    <a href="index.php?cancel=1" class="btn btn-secondary">Annuler</a>
    <?php
}

include "includes/footer.php";
