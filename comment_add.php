<?php

// include de sessie en de config
require "session.inc.php";
require "config.inc.php";

// haal alle gegevens op uit de post
$blogID = base64_decode($_POST['blog']);
$comment = htmlentities($_POST['comment'], ENT_QUOTES);

// encode de blog id
$blogIDEncoded = base64_encode($blogID);

// controleer of de comment groter is dan 200 characters

if(strlen($comment) > 200){
    header("location:blog_info.php?id=$blogIDEncoded&msg=1");
    exit();
}

// maak een var aan voor de datum van vandaag
$now = new DateTime();
$now = date_format($now, 'Y-m-d');

// maak een query voor het invoegen van een comment
$reactieInvoegQuery = "INSERT INTO `reacties` (`ID`, `Informatie`, `Datum`)
                       VALUES (NULL, '$comment', '$now' );";

// voer het comment query uit
mysqli_query($mysqli, $reactieInvoegQuery);

// haal het laatst aangemaakt id uit het database
$id = mysqli_insert_id($mysqli);

// maak een query voor de persoon reactie
$persoonReactieQuery = "INSERT INTO `reactie_persoon_verband` (`PersoonsID`, `ReactieID`)
                       VALUES ('$session_id', '$id' );";

// voer het persoon reactie query uit
mysqli_query($mysqli, $persoonReactieQuery);

// maak een query voor de blog reactie
$blogReactieQuery = "INSERT INTO `blog_reactie_verband` (`BlogID`, `ReactieID`)
                       VALUES ('$blogID', '$id');";

// voer het blog reactie query uit
mysqli_query($mysqli, $blogReactieQuery);

header("location:blog_info.php?id=$blogIDEncoded");

?>