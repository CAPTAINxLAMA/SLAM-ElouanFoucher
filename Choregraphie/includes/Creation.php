<?php

session_start();

$etape = filter_input(INPUT_POST, 'etape', FILTER_DEFAULT);

$MvtAngle = "";
$MvtTime = "";
$AffText = "";
$AffTime = "";
$SonNote = "";
$SonTime = "";

if ($etape == "0")
{
    $tokenServeur = $_SESSION['tokenMvt'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenMvt', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        $_SESSION['MvtAngle'] = filter_input(INPUT_POST, 'MvtAngle', FILTER_DEFAULT);
        $_SESSION['MvtTime'] = filter_input(INPUT_POST, 'MvtTime', FILTER_DEFAULT);
        header("Location: creer.php?etape=1");
    }
}

else if ($etape == "1")
{
    $tokenServeur = $_SESSION['tokenAff'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenAff', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        $_SESSION['AffText'] = filter_input(INPUT_POST, 'AffText', FILTER_DEFAULT);
        $_SESSION['AffTime'] = filter_input(INPUT_POST, 'AffTime', FILTER_DEFAULT);
        header("Location: creer.php?etape=2");
    }
}

else if ($etape == "2")
{
    $tokenServeur = $_SESSION['tokenSon'];
    $tokenRecu = filter_input(INPUT_POST, 'tokenSon', FILTER_DEFAULT);

    if ($tokenRecu != $tokenServeur)
    {
        die("Erreur de token. Vas mourir vilain hacker");
    }
    else
    {
        $_SESSION['SonNote'] = filter_input(INPUT_POST, 'SonNote', FILTER_DEFAULT);
        $_SESSION['SonTime'] = filter_input(INPUT_POST, 'SonTime', FILTER_DEFAULT);
        $etape = 3;
    }
}

if ($etape == "3")
{
    include "../header.php";
    $token = rand(0, 1000000);
    $_SESSION['token'] = $token;
    ?>
    <table class="table table-stripped">
        <tr>
            <th>1</th>
            <th>2</th>
            <th></th>
        </tr>
        <tr>
            <td><?php echo $_SESSION['MvtAngle'] ?></td>
            <td><?php echo $_SESSION['MvtTime'] ?></td>
            <td><a href="creer.php?etape=0" class="btn btn-warning">Modifier</a></td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['AffText'] ?></td>
            <td><?php echo $_SESSION['AffTime'] ?></td>
            <td><a href="creer.php?etape=1" class="btn btn-warning">Modifier</a></td>
        </tr>
        <tr>
            <td><?php echo $_SESSION['SonNote'] ?></td>
            <td><?php echo $_SESSION['SonTime'] ?></td>
            <td><a href="creer.php?etape=2" class="btn btn-warning">Modifier</a></td>
        </tr>
    </table>

    <form action="../actions/create.php" method="post">
        <input type="hidden" name="MvtAngle" value="<?php echo $_SESSION['MvtAngle']; ?>">
        <input type="hidden" name="MvtTime" value="<?php echo $_SESSION['MvtTime']; ?>">
        <input type="hidden" name="AffText" value="<?php echo $_SESSION['AffText']; ?>">
        <input type="hidden" name="AffTime" value="<?php echo $_SESSION['AffTime']; ?>">
        <input type="hidden" name="SonNote" value="<?php echo $_SESSION['SonNote']; ?>">
        <input type="hidden" name="SonTime" value="<?php echo $_SESSION['SonTime']; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="submit" value="Créer" class="btn btn-success">
    </form>

    <?php
    include "../footer.php";
}