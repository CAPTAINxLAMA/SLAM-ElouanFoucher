<?php
session_start();

$supprimer = filter_input(INPUT_GET, "supprimer", FILTER_VALIDATE_INT);
if ($supprimer !== null && $supprimer !== false && isset($_SESSION['sons'])) {
    if (count($_SESSION['sons']) >= 1 && isset($_SESSION['sons'][$supprimer])) {
        unset($_SESSION['sons'][$supprimer]);
        $_SESSION['sons'] = array_values($_SESSION['sons']);
    }
    header("Location: creer.php?etape=2&modifier=1");
    exit();
}

$etape = filter_input(INPUT_GET, "etape", FILTER_VALIDATE_INT);
$modifier = filter_input(INPUT_GET, "modifier", FILTER_VALIDATE_INT);

$token = rand(0, 1000000);
$_SESSION['tokenSon'] = $token;

if ($modifier == 1 && isset($_SESSION['sons'])) {
    $nbSons = count($_SESSION['sons']);
} else {
    $nbSons = isset($_SESSION['nbSons']) ? $_SESSION['nbSons'] : 1;
}

$sons = isset($_SESSION['sons']) ? $_SESSION['sons'] : [];
?>

<h1>Définir les sons</h1>
<form action="Creation.php" method="post">
    <?php for ($i = 0; $i < $nbSons; $i++): ?>
        <fieldset style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">
            <legend>Son <?php echo ($i + 1); ?>
                <?php if ($nbSons >= 1): ?>
                    <button type="submit" name="action_supprimer" value="<?php echo $i; ?>" class="btn btn-danger btn-sm btn-supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce son ?')" formnovalidate>✕ Supprimer</button>
                <?php endif; ?>
            </legend>
            Choisir un fichier audio :
            <select name="SonNote[]" id="selectSon<?php echo $i; ?>"required>
                <option value="">-- Sélectionner un son --</option>
                <option value="Alarme.mp3" <?php echo (isset($sons[$i]['note']) && $sons[$i]['note'] == 'Alarme.mp3') ? 'selected' : ''; ?>>
                    🔔 Alarme
                </option>
                <option value="Applaudissement.mp3" <?php echo (isset($sons[$i]['note']) && $sons[$i]['note'] == 'Applaudissement.mp3') ? 'selected' : ''; ?>>
                    👏 Applaudissement
                </option>
                <option value="Atchoum.mp3" <?php echo (isset($sons[$i]['note']) && $sons[$i]['note'] == 'Atchoum.mp3') ? 'selected' : ''; ?>>
                    🤧 Atchoum
                </option>
            </select>
            <button type="button" class="btn btn-info btn-sm btn-tester-son" onclick="playSound(<?php echo $i; ?>)">
                🔊 Tester le son
            </button>

            <audio id="audioPlayer<?php echo $i; ?>" style="display: none;" src="Sons/<?php echo htmlspecialchars($sons[$i]['note']); ?>"></audio>
            <br>

            Volume :
            <input type="range" name="SonVolume[]" min="0" max="100" value="<?php echo isset($sons[$i]['volume']) ? htmlspecialchars($sons[$i]['volume']) : '50'; ?>" oninput="this.nextElementSibling.value = this.value + '%'">
            <output><?php echo isset($sons[$i]['volume']) ? htmlspecialchars($sons[$i]['volume']) : '50'; ?>%</output>
            <br>
        </fieldset>
    <?php endfor; ?>

    <input type="hidden" name="etape" value="<?php echo $etape; ?>">
    <input type="hidden" name="tokenSon" value="<?php echo $token; ?>">

    <button type="submit" name="action" value="ajouter" class="btn btn-primary">Nouveau Son</button>
    <input type="submit" name="action" value="Suivant" class="btn btn-success">
</form>
<script>
    function playSound(index) {
        const select = document.getElementById('selectSon' + index);
        const audio = document.getElementById('audioPlayer' + index);

        if (!select || !audio) return;

        const fichierChoisi = select.value;

        if (fichierChoisi === "") {
            alert("Veuillez d'abord sélectionner un son dans la liste !");
            return;
        }

        audio.src = "Sons/" + fichierChoisi;

        // 5. Réinitialiser et jouer
        audio.pause();
        audio.currentTime = 0;


        audio.play().catch(err => console.error("Erreur lecture :", err));
    }
</script>