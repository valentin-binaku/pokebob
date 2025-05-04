<?php 
include "../common/config.php";
include "../common/permissionAdmin.php";
include "../common/head.php";
include "../common/navbar.php";
include "../common/footer.php";

$id = $_GET['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $attaque = $_POST['attaque'] ?? 0;
    $pv = $_POST['pv'] ?? 0;
    $rarete = $_POST['rarete'] ?? 'common';
    $image = $_POST['image'] ?? '';

    $stmt = $pdo->prepare("UPDATE carte SET carteNom = :nom, carteDescription = :description, carteAttaque = :attaque,
                            cartePV = :pv, carteRareté = :rarete, image = :image WHERE carteID = :id");

    try {
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':attaque' => $attaque,
            ':pv' => $pv,
            ':rarete' => $rarete,
            ':image' => $image,
            ':id' => $id
        ]);
        $message = "Carte modifiée avec succès.";
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM carte WHERE carteID = :id");
$stmt->execute([':id' => $id]);
$carte = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h1>Modifier la carte : <?= htmlspecialchars($carte['carteNom']) ?></h1>

<form method="post">
    <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($carte['carteNom']) ?>" required></label><br><br>
    <label>Description : <textarea name="description"><?= htmlspecialchars($carte['carteDescription']) ?></textarea></label><br><br>
    <label>Attaque : <input type="number" name="attaque" value="<?= $carte['carteAttaque'] ?>" min="0"></label><br><br>
    <label>PV : <input type="number" name="pv" value="<?= $carte['cartePV'] ?>" min="0"></label><br><br>
    <label>Rareté :
        <select name="rarete">
            <?php
            $raretes = ['common', 'uncommon', 'rare', 'legendary'];
            foreach ($raretes as $r) {
                $selected = ($carte['carteRarete'] === $r) ? "selected" : "";
                echo "<option value=\"$r\" $selected>" . ucfirst($r) . "</option>";
            }
            ?>
        </select>
    </label><br><br>
    <label>Image (nom de fichier) :
        <input type="text" name="image" value="<?= htmlspecialchars($carte['image']) ?>">
    </label><br><br>
    <button type="submit">Modifier la carte</button>
</form>