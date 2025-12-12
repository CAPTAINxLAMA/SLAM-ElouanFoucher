<?php

session_start();
$tokenServeur = $_SESSION['token'];
$tokenRecu = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);

if ($tokenRecu != $tokenServeur)
{
    die("Erreur de token. Vas mourir vilain hacker");
}
echo "Tout est fonctionnel !";