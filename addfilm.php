<?php
$xmlFile = 'XML/cinema.xml';

// Chargement du fichier XML
$xml = simplexml_load_file($xmlFile);

// Vérification du chargement du fichier
if ($xml === false) {
    die('Erreur lors du chargement du fichier XML.');
}

// Ajout d'un film
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add') {
    $newFilm = $xml->addChild('film');
    $newFilm->addAttribute('id', $_POST['id']);
    $newFilm->addChild('titre', $_POST['titre']);
    $newFilm->addChild('image')->addAttribute('url', $_POST['image_url']);
    $newFilm->addChild('duree', $_POST['duree']);
    $newFilm->addChild('genre', $_POST['genre']);
    $newFilm->addChild('realisateur', $_POST['realisateur']);
    $newFilm->addChild('annee', $_POST['annee']);
    $newFilm->addChild('langue', $_POST['langue']);
    $newFilm->addChild('synopsis', $_POST['synopsis']);

    // Ajout des horaires
    $horaires = $newFilm->addChild('horaires');
    for ($i = 1; $i <= $_POST['nombre_horaires']; $i++) {
        if (!empty($_POST['jour' . $i]) && !empty($_POST['heure' . $i])) {
            $horaire = $horaires->addChild('horaire');
            $horaire->addAttribute('jour', $_POST['jour' . $i]);
            $horaire->addAttribute('heure', $_POST['heure' . $i]);
        }
    }

    // Sauvegarde du fichier XML
    $xml->asXML($xmlFile);

    // Redirection vers la même page après ajout
    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Film</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un Nouveau Film</h1>

        <!-- Formulaire d'ajout de film -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="action" value="add">

            <label for="id">ID du Film:</label><br>
            <input type="text" id="id" name="id" required><br><br>

            <label for="titre">Titre:</label><br>
            <input type="text" id="titre" name="titre" required><br><br>

            <label for="image_url">URL de l'image:</label><br>
            <input type="text" id="image_url" name="image_url"><br><br>

            <label for="duree">Durée:</label><br>
            <input type="text" id="duree" name="duree"><br><br>

            <label for="genre">Genre:</label><br>
            <input type="text" id="genre" name="genre"><br><br>

            <label for="realisateur">Réalisateur:</label><br>
            <input type="text" id="realisateur" name="realisateur"><br><br>

            <label for="annee">Année de sortie:</label><br>
            <input type="text" id="annee" name="annee"><br><br>

            <label for="langue">Langue:</label><br>
            <input type="text" id="langue" name="langue"><br><br>

            <label for="synopsis">Synopsis:</label><br>
            <textarea id="synopsis" name="synopsis"></textarea><br><br>

            <label for="nombre_horaires">Nombre d'horaires:</label>
            <input type="number" id="nombre_horaires" name="nombre_horaires" min="1" value="1"><br><br>

            <!-- Champs d'horaires variables -->
            <div id="horaires">
                <label for="jour1">Jour 1:</label>
                <input type="text" id="jour1" name="jour1" required>
                <label for="heure1">Heure 1:</label>
                <input type="text" id="heure1" name="heure1" required><br><br>
            </div>

            <!-- Bouton pour ajouter un nouvel horaire -->
            <button type="button" onclick="addHoraire()">Ajouter un Horaire</button><br><br>

            <button type="submit">Ajouter</button>
        </form>
    </div> <!-- Fin div .container -->

    <script>
        // Fonction pour ajouter dynamiquement des champs d'horaires
        function addHoraire() {
            var nombre_horaires = document.getElementById('nombre_horaires').value;
            var divHoraires = document.getElementById('horaires');

            var newDiv = document.createElement('div');
            var labelJour = document.createElement('label');
            var inputJour = document.createElement('input');
            var labelHeure = document.createElement('label');
            var inputHeure = document.createElement('input');

            labelJour.textContent = 'Jour ' + (parseInt(nombre_horaires) + 1) + ':';
            inputJour.setAttribute('type', 'text');
            inputJour.setAttribute('name', 'jour' + (parseInt(nombre_horaires) + 1));
            inputJour.setAttribute('required', 'true');

            labelHeure.textContent = 'Heure ' + (parseInt(nombre_horaires) + 1) + ':';
            inputHeure.setAttribute('type', 'text');
            inputHeure.setAttribute('name', 'heure' + (parseInt(nombre_horaires) + 1));
            inputHeure.setAttribute('required', 'true');

            newDiv.appendChild(labelJour);
            newDiv.appendChild(inputJour);
            newDiv.appendChild(labelHeure);
            newDiv.appendChild(inputHeure);

            divHoraires.appendChild(newDiv);

            // Mettre à jour le nombre d'horaires
            document.getElementById('nombre_horaires').value = parseInt(nombre_horaires) + 1;
        }
    </script>
</body>
</html>
