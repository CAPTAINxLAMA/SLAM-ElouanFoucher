<?php include "../header.php";

// Récupération de l'avancement de l'étape
$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);

if ($etape === 0)
{
    include "Mouvement.php";
    ?>
    <a href="../index.php" class="btn btn-primary">Prédédent</a>
    <?php
}

else if ($etape === 1)
{
    include "Affichage.php";
    ?>
    <a href="creer.php?etape=0" class="btn btn-primary">Prédédent</a>
    <?php
}

else if ($etape === 2)
{
    include "Son.php";
    ?>
    <a href="creer.php?etape=1" class="btn btn-primary">Prédédent</a>
    <?php
}

include "../footer.php";
