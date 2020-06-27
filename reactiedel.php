<?php

// voeg de config toe
require "config.inc.php";

// haal het id op
$reactieID = $_GET['id'];
$blogID = $_GET['blog'];

// maak een encoded versie
$reactieIDEncode = base64_encode($blogID);

// maak een query voor het verweideren van de reactie
$likeDelQuery = "DELETE FROM `likes` WHERE `likes`.`ReactieID` = $reactieID";

// voer de query uit
mysqli_query($mysqli, $likeDelQuery);

// maak een query voor het verweideren van de reactie
$reactieDelQuery = "DELETE FROM `reacties` WHERE `reacties`.`ID` = $reactieID";

// voer de query uit
mysqli_query($mysqli, $reactieDelQuery);


// stuur terug naar blog info
header("location:blog_info.php?id=$reactieIDEncode")
?>