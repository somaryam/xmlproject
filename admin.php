<?php
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: login.php"); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

// Déconnexion de l'utilisateur
if(isset($_GET['logout'])) {
    session_destroy(); // Détruire toutes les données de session
    header("Location: login.php"); // Rediriger vers la page de connexion après déconnexion
    exit;
}

// Code PHP pour le chargement et l'affichage des films à partir du fichier XML
$xmlFile = 'XML/cinema.xml';
$xml = simplexml_load_file($xmlFile);

// Vérification du chargement du fichier XML
if ($xml === false) {
    die('Erreur lors du chargement du fichier XML.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration des Films</title>
    <style>
        /* Styles CSS ici */
    </style>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Administration des Films</h1>

        <!-- Bouton de déconnexion -->
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="?logout=true">Déconnexion</a>
        </div>

        <!-- Image -->
        <div class="image-container">
            <img src="imagescinoch/cinoch.jpg" alt="Cinéma">
        </div>

        <!-- Liens vers les différentes fonctionnalités -->
        <div>
            <ul>
                <li><a href="addfilm.php">Ajouter un Film</a></li>
                <li><a href="editfilm.php">Modifier un Film</a></li>
                <li><a href="deletefilm.php">Supprimer un Film</a></li>
            </ul>

            <!-- Affichage des films -->
            <h2>Liste des Films</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Affichage des films à partir du XML chargé
                foreach ($xml->film as $film) {
                    echo '<tr>';
                    echo '<td>' . $film['id'] . '</td>';
                    echo '<td>' . $film->titre . '</td>';
                    echo '<td>' . $film->annee . '</td>';
                    echo '<td><a href="editfilm.php?id=' . $film['id'] . '">Modifier</a> | <a href="deletefilm.php?id=' . $film['id'] . '">Supprimer</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div> <!-- Fin div .container -->
    </div>
</body>
</html>
