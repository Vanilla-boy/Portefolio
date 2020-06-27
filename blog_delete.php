<?php

// voeg de config toe
require "config.inc.php";

// haal het id op
$blogID = $_GET['id'];

// maak een query voor het deleten van de reacties
$reactieDeleteQuery = "DELETE `reacties` 
FROM `reacties`
        LEFT JOIN
    `blog_reactie_verband` ON `blog_reactie_verband`.`ReactieID` = `reacties`.`ID`
WHERE
    `blog_reactie_verband`.`BlogID` = $blogID";
// voer de query uit
mysqli_query($mysqli, $reactieDeleteQuery);


// maak een blog query
$blogDeleteQuery = "DELETE `blogs` FROM `blogs` WHERE `blogs`.`ID` = $blogID";


mysqli_query($mysqli, $blogDeleteQuery);

// stuur terug naar home page
header("location:blogs.php");

?>