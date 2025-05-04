<?php 
include "../common/config.php";
include "../common/permissionAdmin.php";
include "../common/head.php";
include "../common/navbar.php";

$id = $_GET['id'] ?? null;

$stmt = $pdo->prepare("DELETE FROM carte WHERE carteID = :id");

try {
    $stmt->execute([':id' => $id]);
    header("Location: card_list.php");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression : " . $e->getMessage());
}
?>

<?php
include "../common/footer.php";
?>