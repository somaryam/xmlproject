<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Portail Cinéma et Restaurants</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="dark-mode">
    <div class="container">
        <header>
            <h1>Bienvenue sur notre portail</h1>
            <p>Découvrez notre sélection de films et de restaurants.</p>
        </header>
        <main>
            <section class="section-links">
                <a href="cinema.php" class="button">Voir les films à l'affiche</a>
                <a href="restaurant.php" class="button">Découvrir nos restaurants</a>
            </section>
            <section class="section-featured">
                <h2>Films à l'affiche</h2>
                <div class="film-list">
                    <?php
                    // Chargement du fichier XML
                    $xml = simplexml_load_file('XML/cinema.xml');

                    // Vérification du chargement du fichier
                    if ($xml === false) {
                        echo "Erreur lors du chargement du fichier XML.";
                        exit;
                    }

                    // Affichage des films à l'affiche
                    foreach ($xml->film as $film) {
                        echo '<div class="film">';
                        echo '<img src="' . $film->image['url'] . '" alt="' . $film->titre . '">';
                        echo '<h3>' . $film->titre . '</h3>';
                        echo '<p><strong>Genre:</strong> ' . $film->genre . '</p>';
                        echo '<p><strong>Réalisateur:</strong> ' . $film->realisateur . '</p>';
                        echo '<a href="cinema.php" class="button">Voir les détails</a>';
                        echo '</div>'; // Fin div .film
                    }
                    ?>
                </div>
            </section>
        </main>
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Portail Cinéma et Restaurants</p>
        </footer>
    </div> <!-- Fin div .container -->
</body>
</html>
