<?php
$xmlFile = 'XML/cinema.xml';

// Chargement du fichier XML
$xml = simplexml_load_file($xmlFile);

// Vérification du chargement du fichier
if ($xml === false) {
    die('Erreur lors du chargement du fichier XML.');
}

// Traitement de la modification d'un film
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $filmId = $_POST['film_id'];

    // Recherche du film à modifier
    $filmToEdit = null;
    foreach ($xml->film as $film) {
        if ($film['id'] == $filmId) {
            $filmToEdit = $film;
            break;
        }
    }

    // Si le film est trouvé, mettre à jour ses données
    if ($filmToEdit !== null) {
        $filmToEdit->titre = $_POST['titre'];
        $filmToEdit->image->attributes()['url'] = $_POST['image_url'];
        $filmToEdit->duree = $_POST['duree'];
        $filmToEdit->genre = $_POST['genre'];
        $filmToEdit->realisateur = $_POST['realisateur'];
        $filmToEdit->annee = $_POST['annee'];
        $filmToEdit->langue = $_POST['langue'];
        $filmToEdit->synopsis = $_POST['synopsis'];

        // Suppression des horaires existants
        unset($filmToEdit->horaires->horaire);

        // Ajout des nouveaux horaires
        for ($i = 1; $i <= $_POST['nombre_horaires']; $i++) {
            if (!empty($_POST['jour' . $i]) && !empty($_POST['heure' . $i])) {
                $horaire = $filmToEdit->horaires->addChild('horaire');
                $horaire->addAttribute('jour', $_POST['jour' . $i]);
                $horaire->addAttribute('heure', $_POST['heure' . $i]);
            }
        }

        // Sauvegarde du fichier XML
        $xml->asXML($xmlFile);

        // Redirection vers la même page après modification
        header('Location: admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Film</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un Film</h1>

        <!-- Formulaire de modification de film -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="action" value="edit">

            <label for="film_id">Choisir un Film:</label><br>
            <select name="film_id" id="film_id">
                <?php foreach ($xml->film as $film): ?>
                    <option value="<?php echo $film['id']; ?>"><?php echo $film->titre; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" onclick="loadFilmDetails()">Charger</button><br><br>

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

            <button type="submit">Enregistrer les Modifications</button>
        </form>
    </div> <!-- Fin div .container -->

    <script>
        // Fonction pour charger les détails d'un film sélectionné
        function loadFilmDetails() {
            var selectedFilmId = document.getElementById('film_id').value;

            // Requête AJAX pour récupérer les détails du film sélectionné
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var filmDetails = JSON.parse(xhr.responseText);

                        // Remplir les champs du formulaire avec les détails du film
                        document.getElementById('titre').value = filmDetails.titre;
                        document.getElementById('image_url').value = filmDetails.image_url;
                        document.getElementById('duree').value = filmDetails.duree;
                        document.getElementById('genre').value = filmDetails.genre;
                        document.getElementById('realisateur').value = filmDetails.realisateur;
                        document.getElementById('annee').value = filmDetails.annee;
                        document.getElementById('langue').value = filmDetails.langue;
                        document.getElementById('synopsis').value = filmDetails.synopsis;

                        // Supprimer les horaires existants
                        var horairesDiv = document.getElementById('horaires');
                        while (horairesDiv.firstChild) {
                            horairesDiv.removeChild(horairesDiv.firstChild);
                        }

                        // Ajouter les nouveaux champs d'horaires
                        filmDetails.horaires.forEach(function(horaire, index) {
                            var newDiv = document.createElement('div');
                            var labelJour = document.createElement('label');
                            var inputJour = document.createElement('input');
                            var labelHeure = document.createElement('label');
                            var inputHeure = document.createElement('input');

                            labelJour.textContent = 'Jour ' + (index + 1) + ':';
                            inputJour.setAttribute('type', 'text');
                            inputJour.setAttribute('name', 'jour' + (index + 1));
                            inputJour.setAttribute('required', 'true');
                            inputJour.value = horaire.jour;

                            labelHeure.textContent = 'Heure ' + (index + 1) + ':';
                            inputHeure.setAttribute('type', 'text');
                            inputHeure.setAttribute('name', 'heure' + (index + 1));
                            inputHeure.setAttribute('required', 'true');
                            inputHeure.value = horaire.heure;

                            newDiv.appendChild(labelJour);
                            newDiv.appendChild(inputJour);
                            newDiv.appendChild(labelHeure);
                            newDiv.appendChild(inputHeure);

                            horairesDiv.appendChild(newDiv);
                        });
                    } else {
                        alert('Erreur lors du chargement des détails du film.');
                    }
                }
            };

            xhr.open('GET', 'getFilmDetails.php?id=' + selectedFilmId, true);
            xhr.send();
        }

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