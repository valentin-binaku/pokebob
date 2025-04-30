<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
</head>
<body>
<?php

session_start();

include "../common/config.php";
include "../common/permission.php";
include "../common/head.php";
include "../common/navbar.php";

if (!isset($_SESSION['user_id'])) {
    echo "Vous devez être connecté pour accéder à cette page.";
    exit();
}

echo "(Afficher photo de profil)" . "<br>";
echo "" . "<br>";

$userID = $_SESSION['user_id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pokebob", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT utilNom, utilEmail, utilMoney FROM utilisateur WHERE utilID = :id");
    //$stmt->bindParam(":id", $userID);
    $stmt->execute(['id' => $userID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "Nom d'utilisateur : " . htmlspecialchars($user['utilNom']) . "<br>";
        echo "Email : " . htmlspecialchars($user['utilEmail']) . "<br>";
        echo "Nombre de BobCoin : " . htmlspecialchars($user['utilMoney']) . "<br>";

    } else {
        echo "Utilisateur non trouvé";
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

include "../common/footer.php";

?>

</body>
</html>

