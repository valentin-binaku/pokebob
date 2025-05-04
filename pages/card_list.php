<?php 
include "../common/config.php";
include "../common/permission.php";
include "../common/head.php";
include "../common/navbar.php";

$search = $_GET['search'] ?? '';
$isAdmin = isset($_SESSION['utilDroit']) && $_SESSION['utilDroit'] === 'admin';

// Préparer la requête avec ou sans recherche
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM carte WHERE carteNom LIKE :search");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM carte");
}
$cartes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Liste des cartes</h1>

<!-- Barre de recherche -->
<form method="get" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Rechercher une carte..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Rechercher</button>
</form>

<!-- Tableau des cartes -->
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Attaque</th>
            <th>PV</th>
            <th>Rareté</th>
            <th>Image</th>
            <?php if ($isAdmin): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
        <?php
         foreach ($cartes as $carte): 
        ?>
            <tr>
                <td><?= htmlspecialchars($carte['carteNom']) ?></td>
                <td><?= nl2br(htmlspecialchars($carte['carteDescription'])) ?></td>
                <td><?= $carte['carteAttaque'] ?></td>
                <td><?= $carte['cartePV'] ?></td>
                <td><?= ucfirst($carte['carteRareté']) ?></td>
                <td>
                    <?php if (!empty($carte['image'])): 
                        echo "<img src='../public/image/{$carte['image']}' alt='{$carte['carteNom']}' style='height:80px;'>";
                    ?>
                    <?php else: ?>
                        Aucune
                    <?php endif; ?>
                </td>
                <?php if ($_SESSION["utilDroit"]=="admin"): ?>
                    <td>
                        <a href="edit_card.php?id=<?= $carte['carteID'] ?>">Modifier</a> |
                        <a href="delete_card.php?id=<?= $carte['carteID'] ?>" onclick="return confirm('Supprimer cette carte ?');">Supprimer</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
</table>

<?php include "../common/footer.php"; ?>
