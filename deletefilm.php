<?php
$xmlFile = 'XML/cinema.xml';

// Chargement du fichier XML
$xml = simplexml_load_file($xmlFile);

// Vérification du chargement du fichier
if ($xml === false) {
    die('Erreur lors du chargement du fichier XML.');
}

// Suppression d'un film
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $idToDelete = $_POST['id'];

    // Recherche du film par son ID
    $filmToDelete = null;
    foreach ($xml->film as $film) {
        if ($film['id'] == $idToDelete) {
            $filmToDelete = $film;
            break;
        }
    }

    // Si le film est trouvé, le supprimer
    if ($filmToDelete) {
        $domFilm = dom_import_simplexml($filmToDelete);
        if ($domFilm && $domFilm->parentNode) {
            $domFilm->parentNode->removeChild($domFilm);
        }

        // Sauvegarde du fichier XML
        $xml->asXML($xmlFile);

        // Redirection vers la même page après suppression
        header('Location: admin.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration des Films</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Administration des Films</h1>

        <!-- Tableau des films -->
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($xml->film as $film): ?>
                <tr>
                    <td><?php echo $film->titre; ?></td>
                    <td><?php echo $film->annee; ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $film['id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> <!-- Fin div .container -->
</body>
</html>
