<?php
// Chargement à nouveau du fichier XML pour afficher les films
$xml = simplexml_load_file('XML/cinema.xml');

// Vérification du chargement du fichier
if ($xml === false) {
    die('Erreur lors du chargement du fichier XML.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Programme Cinéma</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Programme Cinéma</h1>
        <!-- Liste des films existants -->
        <div class="film-list">
            <?php foreach ($xml->film as $id => $film): ?>
                <div class="film">
                    <h2><?php echo $film->titre; ?></h2>
                    <img src="<?php echo $film->image['url']; ?>" alt="<?php echo $film->titre; ?>">
                    <p><strong>Durée:</strong> <?php echo $film->duree; ?></p>
                    <p><strong>Genre:</strong> <?php echo $film->genre; ?></p>
                    <p><strong>Réalisateur:</strong> <?php echo $film->realisateur; ?></p>
                    <p><strong>Acteurs:</strong> <?php echo $film->acteurs; ?></p>
                    <p><strong>Année:</strong> <?php echo $film->annee; ?></p>
                    <p><strong>Langue:</strong> <?php echo $film->langue; ?></p>
                    <p><strong>Synopsis:</strong> <?php echo $film->synopsis; ?></p>
                  
                    <!-- Affichage des notes s'il y en a -->
                    <?php if (isset($film->note_presse)): ?>
                        <p><strong>Note de la presse:</strong> <?php echo $film->note_presse; ?></p>
                    <?php endif; ?>
                    <?php if (isset($film->note_spectateurs)): ?>
                        <p><strong>Note des spectateurs:</strong> <?php echo $film->note_spectateurs; ?></p>
                    <?php endif; ?>

                    <!-- Affichage des horaires -->
                    <p><strong>Horaires:</strong></p>
                    <ul>
                        <?php foreach ($film->horaires->horaire as $horaire): ?>
                            <li>Jour: <?php echo $horaire['jour']; ?>, Heure: <?php echo $horaire['heure']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div> <!-- Fin div .container -->
</body>
</html>

