<style>
form {
            display: none; /* Formulaire caché par défaut */
        }

        form.active {
            display: block; /* Affiche le formulaire actif */
        }
</style>
<?php

if ($user && password_verify($password, $user['utilMdp'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['user_id'] = $user['utilID'];
    header("Location: index.php?page=account");
    exit();
}

include "common/config.php";
$page = isset($_GET['page']) ? basename($_GET['page']) : 'home';
$pages = ['index', 'compte', 'detail'];
include "common/head.php";


if (in_array($page, $pages)) {
    include 'pages/' . $page . '.php';
} else {
    include '404.php';
}


// se connecter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_nom']) && isset($_POST['login_password'])) {
    $nom = trim($_POST['login_nom']);
    $password = $_POST['login_password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilNom = :nom");
        $stmt->bindParam(":nom", $nom);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['utilMdp'])) {
            $_SESSION['user_id'] = $user['utilID'];  // Stocker l'ID utilisateur
            $_SESSION['utilNom'] = $user['utilNom']; // Stocker le pseudo
            $_SESSION['utilDroit'] = $user['utilType']; // Stocker le type (admin ou utilisateur)
            $_SESSION["connected"] = true;

            header("Location: pages/home.php");
            exit();
        } else {
            echo "<script>alert('Identifiants incorrects !');</script>";
        }
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

    // s'inscrire
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_nom']) && isset($_POST['register_mail']) && isset($_POST['register_password'])) {
        $nom = trim($_POST['register_nom']);
        $mail = trim($_POST['register_mail']);
        $password = $_POST['register_password'];
    
        if (!empty($nom) && !empty($mail) && !empty($password)) {
            try {
                // Vérifier si le nom d'utilisateur existe déjà
                $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilNom = :nom");
                $stmt->bindParam(":nom", $nom);
                $stmt->execute();
    
                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Ce nom d'utilisateur existe deja !');</script>";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO utilisateur (utilNom, utilEmail, utilMdp, utilDateCreation) VALUES (:nom, :mail, :password, NOW())");
                    $stmt->bindParam(":nom", $nom);
                    $stmt->bindParam(":mail", $mail);
                    $stmt->bindParam(":password", $hashedPassword);
                    $stmt->execute();
    
                    echo "<script>alert('Compte créé avec succès !'); window.location.href = 'index.php';</script>";
                    exit();
                }
            } catch (PDOException $e) {
                die("Erreur : " . $e->getMessage());
            }
        } else {
            echo "<script>alert('Veuillez remplir tous les champs.');</script>";
        }
    }
    

?>
<body>
    <h1>Pokebob</h1>
    <h4>Bienvenue sur Pokebob le jeu officiel des Pokebob inscrit toi pour accéder à la suite du site ou alors créer toi un compte. </h4>
    <main class="container">
        <h2>Choisir une option</h2>

        <!-- Radio buttons pour choisir entre connexion ou inscription -->
        <label>
            <input type="radio" name="form_choice" id="login_radio" onclick="toggleForm('login')" checked>
            Se connecter
        </label>
        <label>
            <input type="radio" name="form_choice" id="register_radio" onclick="toggleForm('register')">
            Créer un compte
        </label>

        <!-- Formulaire de connexion -->
        <form id="login_form" method="POST" class="active">
            <h2>Se connecter</h2>
            <label for="login_nom">Identifiant</label>
            <input type="text" name="login_nom" id="login_nom" required>

            <label for="login_password">Mot de passe</label>
            <input type="password" name="login_password" id="login_password" required>

            <button type="submit">Connexion</button>
        </form>

        <!-- Formulaire d'inscription -->
        <form id="register_form" method="POST">
            <h2>Créer un compte</h2>
            <label for="register_nom">Identifiant</label>
            <input type="text" name="register_nom" id="register_nom" required>

            <label for="register_mail">E-Mail</label>
            <input type="email" name="register_mail" id="register_mail" required>

            <label for="register_password">Mot de passe</label>
            <input type="password" name="register_password" id="register_password" required>

            <button type="submit">Créer un compte</button>
        </form>
    </main>

    <script>
        function toggleForm(choice) {
            // Cacher tous les formulaires
            document.getElementById('login_form').classList.remove('active');
            document.getElementById('register_form').classList.remove('active');

            // Afficher le formulaire en fonction du choix
            if (choice === 'login') {
                document.getElementById('login_form').classList.add('active');
            } else if (choice === 'register') {
                document.getElementById('register_form').classList.add('active');
            }
        }
    </script>


<?php
include "common/footer.php";
?>