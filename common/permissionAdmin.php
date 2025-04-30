<?php
if  ($_SESSION["utilDroit"]!="admin"){
    header("Location: ../pages/home.php");
}
?>