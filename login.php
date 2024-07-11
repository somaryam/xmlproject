<?php
session_start();

// Vérifier si l'utilisateur est déjà connecté, le rediriger vers admin.php
if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    header("Location: admin.php"); // Rediriger vers admin.php si l'utilisateur est déjà connecté
    exit;
}

// Définir les informations d'identification (à remplacer par une méthode sécurisée)
$valid_username = 'admin'; // Nom d'utilisateur correct
$valid_password = 'admin'; // Mot de passe correct

// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les informations d'identification depuis le formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier les informations d'identification
    if($username === $valid_username && $password === $valid_password) {
        // Authentification réussie, définir une session
        $_SESSION['authenticated'] = true;
        header("Location: admin.php"); // Rediriger vers admin.php après connexion réussie
        exit;
    } else {
        // Authentification échouée, afficher un message d'erreur
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        /* Styles CSS pour le formulaire de connexion */
        form {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>Connexion</h2>
        <?php if(isset($error_message)) { ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php } ?>
        <div>
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Se connecter">
        </div>
    </form>
</body>
</html>
