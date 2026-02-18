<?php
session_start();
include "includes/config.php";

$pdo = new PDO('mysql:host=' . config::HOST . ';dbname=' . config::DBNAME . ';charset=utf8mb4',
    config::USER, config::PASSWORD);

// Récupérer les réglages actuels
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

<?php
$updated = filter_input(INPUT_GET, "updated", FILTER_VALIDATE_INT);
if ($updated == 1) {
    echo '<div class="alert alert-success">Réglages mis à jour avec succès !</div>';
}
?>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5>Samedi / Dimanche</h5>
                </div>
                <div class="card-body">
                    <form action="actions/update_reglages.php" method="post">
                        <input type="hidden" name="type" value="weekend">

                        <div class="form-group mb-3">
                            <label for="weekend_debut">Heure de début :</label>
                            <input type="time"
                                   id="weekend_debut"
                                   name="heure_debut"
                                   class="form-control"
                                   value="<?php echo $weekend ? substr($weekend['HeureDebut'], 0, 5) : '08:00'; ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="weekend_fin">Heure de fin :</label>
                            <input type="time"
                                   id="weekend_fin"
                                   name="heure_fin"
                                   class="form-control"
                                   value="<?php echo $weekend ? substr($weekend['HeureFin'], 0, 5) : '22:00'; ?>"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Semaine -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5>Lundi - Vendredi</h5>
                </div>
                <div class="card-body">
                    <form action="actions/update_reglages.php" method="post">
                        <input type="hidden" name="type" value="semaine">

                        <div class="form-group mb-3">
                            <label for="semaine_debut">Heure de début :</label>
                            <input type="time"
                                   id="semaine_debut"
                                   name="heure_debut"
                                   class="form-control"
                                   value="<?php echo $semaine ? substr($semaine['HeureDebut'], 0, 5) : '07:00'; ?>"
                                   required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="semaine_fin">Heure de fin :</label>
                            <input type="time"
                                   id="semaine_fin"
                                   name="heure_fin"
                                   class="form-control"
                                   value="<?php echo $semaine ? substr($semaine['HeureFin'], 0, 5) : '23:00'; ?>"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        Les notifications ne seront envoyées que pendant ces plages horaires. En dehors de ces horaires, l'envoi vers l'ESP32 sera bloqué.
    </div>

    <a href="index.php" class="btn btn-primary">Accueil</a>

<?php include "includes/footer.php"; ?>