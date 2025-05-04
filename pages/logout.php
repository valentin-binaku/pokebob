<?php
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['utilNom']);
unset($_SESSION['utilDroit']);
unset($_SESSION['connected']);
header("Location: ../index.php");
exit();
