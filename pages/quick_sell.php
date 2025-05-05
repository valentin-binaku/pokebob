<?php
include "../common/config.php";
include "../common/permission.php";
include "../common/head.php";
include "../common/navbar.php";

$carteID = $_POST['carte_id'];
$gain = $_POST['gain'];
$userID = $_SESSION['user_id'];

$query_stock = "SELECT Stock FROM inventaire WHERE carteID = :carteID AND utilID = :userID";
$stmt_stock = $pdo->prepare($query_stock);
$stmt_stock->bindParam(':carteID', $carteID);
$stmt_stock->bindParam(':userID', $userID);
$stmt_stock->execute();
$stock = $stmt_stock->fetchColumn();

if ($stock > 1) {
    $query_update_stock = "UPDATE inventaire SET Stock = Stock - 1 WHERE carteID = :carteID AND utilID = :userID";
    $stmt_update_stock = $pdo->prepare($query_update_stock);
    $stmt_update_stock->bindParam(':carteID', $carteID);
    $stmt_update_stock->bindParam(':userID', $userID);
    $stmt_update_stock->execute();
} else {
    $query_delete = "DELETE FROM inventaire WHERE carteID = :carteID AND utilID = :userID";
    $stmt_delete = $pdo->prepare($query_delete);
    $stmt_delete->bindParam(':carteID', $carteID);
    $stmt_delete->bindParam(':userID', $userID);
    $stmt_delete->execute();
}

// Ajouter les bobcoins à l'utilisateur
$query2 = "UPDATE utilisateur SET utilMoney = utilMoney + :gain WHERE utilID = :userID";
$stmt2 = $pdo->prepare($query2);
$stmt2->bindParam(':gain', $gain);
$stmt2->bindParam(':userID', $userID);
$stmt2->execute();

header('Location: ../pages/inventory.php');
exit();
?>
