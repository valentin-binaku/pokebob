<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Liste des utilisateurs</title>
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

echo "Liste des utilisateurs :" . "<br>";

$userID = $_SESSION['user_id'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pokebob", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT utilNom, utilEmail, utilMoney FROM utilisateur";
    $stmt = $pdo -> query($sql);
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='3' cellpadding='10' cellspacing='0'>";
    echo "<thead>";
    echo "<tr><th>Nom d'utilisateur</th><th>Email</th><th>BobCoin</th></tr>";
    echo "</thead>";
    echo "<tbody>";

    foreach ($utilisateurs as $user) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['utilNom']) . "</td>";
        echo "<td>" . htmlspecialchars($user['utilEmail']) . "</td>";
        echo "<td>" . htmlspecialchars($user['utilMoney']) . "</td>";
        echo "</tr>";

    }

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

?>
<?php
include "../common/footer.php";
?>
</body>
</html>