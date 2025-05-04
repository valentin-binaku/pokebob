<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
</head>
<body>
<?php 
include "../common/config.php";
include "../common/permission.php";
include "../common/head.php";
include "../common/navbar.php";

try {
    $nom = $_SESSION['utilNom'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("
        SELECT c.carteNom, c.carteRareté, c.carteDescription, c.image, i.Stock
        FROM inventaire i
        INNER JOIN carte c ON i.carteID = c.carteID
        WHERE i.utilID = :userId
    ");
    $stmt->execute(['userId' => $userId]);
    $cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Affichage des cartes
    echo "<div class='grid'>";
    foreach ($cartes as $carte) {
        echo "<article>";
        echo "<img src='../public/image/{$carte['image']}' alt='{$carte['carteNom']}' style='height:200px;'>";
        echo "<p>{$carte['carteNom']}<br><small>{$carte['carteRareté']}</small></p>";
        echo "<p><small>{$carte['carteDescription']}</small></p>";
        echo "<p>x {$carte['Stock']}</p>";
        echo "</article>";
    }
    echo "</div>";

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<?php
include "../common/footer.php";
?>
</body>
</html>
