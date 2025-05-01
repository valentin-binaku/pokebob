<?php
include "../common/config.php";
include "../common/permission.php";
include "../common/head.php";
include "../common/navbar.php";

function tirerCarte() {
    $rand = mt_rand(1, 1000);

    if ($rand <= 2) {
        return 'legendary'; // 0.2%
    } elseif ($rand <= 50) {
        return 'rare'; // 4.8%
    } elseif ($rand <= 300) {
        return 'uncommun'; // 25%
    } else {
        return 'common'; // 70%
    }
}


try {
    $nom = $_SESSION['utilNom'];
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT utilDerniereOuverture FROM utilisateur WHERE utilNom = :nom");
    $stmt->bindParam(":nom", $nom);
    $stmt->execute();
    $dernier = $stmt->fetchColumn();

    $now = time();

    if ($dernier !== false && $now - strtotime($dernier) < 2 * 3600) {
        $tempsRestant = 2 * 3600 - ($now - strtotime($dernier));
        $minutes = ceil($tempsRestant / 60);
        die("⏳ Tu dois attendre encore $minutes minutes pour ouvrir un nouveau pack !");
    }else {
        $cartes = [];
        for ($i = 0; $i < 3; $i++) {
            $type = tirerCarte();
        
            // Récupère une carte aléatoire de ce type dans la base
            $stmt = $pdo->prepare("SELECT * FROM carte WHERE carteRareté = ? ORDER BY RAND() LIMIT 1");
            $stmt->execute([$type]);
            $carte = $stmt->fetch(PDO::FETCH_ASSOC);
            $cartes[] = $carte;
        
            // Enregistre dans l'inventaire
            // Vérifie si l'utilisateur possède déjà la carte
        $stmt = $pdo->prepare("SELECT stock FROM inventaire WHERE utilId = ? AND carteId = ?");
        $stmt->execute([$userId, $carte['carteID']]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            // Carte déjà présente incrémententation du stock
            $stmt = $pdo->prepare("UPDATE inventaire SET stock = stock + 1 WHERE utilId = ? AND carteId = ?");
            $stmt->execute([$userId, $carte['carteID']]);
        } else {
            // Nouvelle carte on l'ajoute
            $stmt = $pdo->prepare("INSERT INTO inventaire (utilId, carteId, stock) VALUES (?, ?, 1)");
            $stmt->execute([$userId, $carte['carteID']]);
        }

        
        }
              //affichage carte obtenu      
        echo "<h2>Tu as obtenu :</h2><div class='grid'>";
        foreach ($cartes as $carte) {
            echo "<article>";
            echo "<img src='public/image/cartes/{$carte['image']}' alt='{$carte['carteNom']}' style='height:200px;'>";
            echo "<p>{$carte['carteNom']}<br><small>{$carte['carteRareté']}</small></p>";
            echo "</article>";
        }
        echo "</div>";
    }

    $stmt = $pdo->prepare("UPDATE utilisateur SET utilDerniereOuverture = NOW() WHERE utilNom = :nom");
    $stmt->bindParam(":nom", $nom);
    $stmt->execute();
    
    
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>
<?php
include "../common/footer.php";
?>