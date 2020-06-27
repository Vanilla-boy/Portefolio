<?php

require "session.inc.php";
// include de config
require "config.inc.php";

// haal de gegevens binnen
$reactieID = base64_decode($_GET['reactieid']);
$blogID = $_GET['blogid'];


if(!is_nan($reactieID)){
    // maak een query voor het toevoegen van een like
    $likeQuery = "INSERT INTO `reactie_likes` (`PersoonsID`, `ReactieID`) VALUES ('$session_id', '$reactieID')";

}


// voer de query uit
mysqli_query($mysqli,$likeQuery);



header("location:blog_info.php?id=$blogID");
?>