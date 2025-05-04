<?php 
include "../common/config.php";
include "../common/permissionAdmin.php";
include "../common/head.php";
include "../common/navbar.php";
include "../common/footer.php";
?>
<form action="admin_user_list.php" method="post">
    <button type="submit">Liste des utilisateurs</button>
</form>
<form action="card_list.php" method="post">
    <button type="submit">Liste des cartes</button>
</form>
<form action="add_card.php" method="post">
    <button type="submit">Ajout d'une carte</button>
</form>