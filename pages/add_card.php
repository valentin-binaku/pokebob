<?php 
include "../common/config.php";
include "../common/permissionAdmin.php";
include "../common/head.php";
include "../common/navbar.php";
include "../common/footer.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'] ?? '';
    $description = $_POST['description'] ?? '';
    $attaque = $_POST['attaque'] ?? 0;
    $pv = $_POST['pv'] ?? 0;
    $rarete = $_POST['rarete'] ?? 'common';
    $image = $_POST['image'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO carte (carteNom, carteDescription, carteAttaque, cartePV, carteRareté, image)
            VALUES (:nom, :description, :attaque, :pv, :rarete, :image)");
    
    try {
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':attaque' => $attaque,
            ':pv' => $pv,
            ':rarete' => $rarete,
            ':image' => $image
        ]);
        $message = "Carte ajoutée avec succès !";
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}
?>

<?php if (!empty($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <label>Nom : <input type="text" name="nom" required></label><br><br>
    <label>Description : <textarea name="description"></textarea></label><br><br>
    <label>Attaque : <input type="number" name="attaque" min="0"></label><br><br>
    <label>PV : <input type="number" name="pv" min="0"></label><br><br>
    <label>Rareté :
        <select name="rarete">
            <option value="common">Common</option>
            <option value="uncommon">Uncommon</option>
            <option value="rare">Rare</option>
            <option value="legendary">Legendary</option>
        </select>
    </label><br><br>
    <label>Image (nom de fichier) :
        <input type="text" name="image">
    </label><br><br>
    <button type="submit">Ajouter la carte</button>
</form>
