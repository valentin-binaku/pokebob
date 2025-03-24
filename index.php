<?php
include "common/config.php";
// Récupération de la route depuis l'URL
$page = isset($_GET['page']) ? basename($_GET['page']) : 'home';

// Définition des pages autorisées
$pages = ['index', 'compte', 'detail'];

include "common/head.php";


// Vérification et inclusion de la bonne page
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

    // s'inscrire\
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_nom']) && isset($_POST['register_mail']) && isset($_POST['register_password'])) {
        $nom = trim($_POST['register_nom']);
        $mail = trim($_POST['register_mail']);
        $password = $_POST['register_password'];
    
        if (!empty($nom) && !empty($mail) && !empty($password)) {
            try {
                // Vérifier si l'email existe déjà
                $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE utilEmail = :mail");
                $stmt->bindParam(":mail", $mail);
                $stmt->execute();
    
                if ($stmt->rowCount() > 0) {
                    echo "<script>alert('Cet email est déjà utilisé !');</script>";
                } else {
                    // Hacher le mot de passe
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
                    // Insérer l'utilisateur
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

    <h2>Se connecter</h2>
    <form method="POST">
        <label for="login_nom">Identifiant</label>
        <input type="text" name="login_nom" id="login_nom" required>

        <label for="login_password">Mot de passe</label>
        <input type="password" name="login_password" id="login_password" required>

        <button type="submit">Connexion</button>
    </form>

    <h2>Créer un compte</h2>
    <form method="POST">
        <label for="register_nom">Identifiant</label>
        <input type="text" name="register_nom" id="register_nom" required>

        <label for="register_mail">E-Mail</label>
        <input type="email" name="register_mail" id="register_mail" required>

        <label for="register_password">Mot de passe</label>
        <input type="password" name="register_password" id="register_password" required>

        <button type="submit">Créer un compte</button>
    </form>
</body>


<?php
include "common/footer.php";
?>