<?php
session_start();
include "includes/config.php";

$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME . ';charset=utf8mb4',
    config::USER, config::PASSWORD);

$reqReglages = $pdo->query("SELECT * FROM reglages_horaires ORDER BY Type");
$reglages = $reqReglages->fetchAll(PDO::FETCH_ASSOC);

$weekend = null;
$semaine = null;

foreach ($reglages as $reglage) {
    if ($reglage['Type'] == 'weekend') {
        $weekend = $reglage;
    } else if ($reglage['Type'] == 'semaine') {
        $semaine = $reglage;
    }
}

include "includes/header.php";
?>

    <h2>Réglages des horaires</h2>

    <div class="alert alert-info">
        Les notifications ne seront envoyées que pendant ces plages horaires. En dehors de ces horaires, l'envoi vers l'ESP32 sera bloqué.
    </div>

<?php
$updated = filter_input(INPUT_GET, "updated", FILTER_VALIDATE_INT);
if ($updated == 1) {
    echo '<div class="alert alert-success">Réglages mis à jour avec succès !</div>';
}

$error = filter_input(INPUT_GET, "error", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($error == 'weekend') {
    echo '<div class="alert alert-danger">Horaires du weekend invalides !</div>';
}
elseif ($error == 'semaine') {
    echo '<div class="alert alert-danger">Horaires de la semaine invalides !</div>';
}
?>

    <form action="actions/update_reglages.php" method="post">
        <table class="table table-bordered">
            <tr>
                <th colspan="2">Samedi / Dimanche</th>
            </tr>
            <tbody>
            <tr>
                <td>
                    <label>Heure de début :</label>
                    <input type="time" name="weekend_debut" value="<?php echo substr($weekend['HeureDebut'], 0, 5); ?>" required>

                    <label>Heure de fin :</label>
                    <input type="time" name="weekend_fin" value="<?php echo substr($weekend['HeureFin'], 0, 5); ?>" required>
                </td>
            </tr>
            </tbody>

            <tr>
                <th colspan="2">Lundi - Vendredi</th>
            </tr>
            <tbody>
            <tr>
                <td>
                    <label>Heure de début :</label>
                    <input type="time" name="semaine_debut" value="<?php echo substr($semaine['HeureDebut'],0,5); ?>" required>

                    <label>Heure de fin :</label>
                    <input type="time" name="semaine_fin" value="<?php echo substr($semaine['HeureFin'],0,5); ?>" required>
                </td>
            </tr>
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
    <br>
    <a href="index.php" class="btn btn-primary">Accueil</a>

<?php include "includes/footer.php"; ?>