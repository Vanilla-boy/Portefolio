<?php

// include de sessie en de config
require "session.inc.php";
require "config.inc.php";

// haal de gegevens binnen
$reactieID = $_GET['reactieid'];
$blogID = $_GET['blogid'];

// maak een query voor het deleten van de like
$likeDelQuery = "DELETE FROM `reactie_likes` WHERE `ReactieID` = $reactieID AND `PersoonsID` = $session_id";
// voer de query uit
mysqli_query($mysqli, $likeDelQuery);

// stuur terug naar blog
header("location:blog_info.php?id=$blogID");

?>