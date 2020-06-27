<?php

// include de config
require "config.inc.php";

// haal alle gegevens op uit de form

$title = $_POST['projectNaam'];
$info = $_POST['omschrijving'];
$foto = $_FILES['foto'];
$style = $_POST['style'];
$str = json_decode($_POST['rollen'], true);


// kijk of de title niet groter is dan een x aantal
    if(strlen($title) > 64){
        // stuur terug naar blog add
        header("location:blogs.php");
    }

    // kijk of de omschrijving groter is dan een x aantal
    if(strlen($info) > 800){
        // stuur terug naar blog add
        header("location:blogs.php");
    }

    // kijk of de rollen niet leeg zijn
    if(!isset($tags)){
        // stuur terug naar blog add
        header("location:blogs.php");
    }

    // kijk of de foto leeg is{
    if(!isset($foto)){
        // stuur terug naar blog add
        header("location:blogs.php");
    }

// maak een datum var aan van vandaag
    $datum = date("Y-m-d");
// voeg de title en omschrijving en style en datum toe aan het database
$blogInvoegQuery = "INSERT INTO `blogs` (`ID`, `OnderwerpNaam`, `Informatie`, `DatumAanmaak`, `Style`)
                    VALUES (NULL, '$title', '$info', '$datum', '$style')";

// voeg de blog toe
mysqli_query($mysqli,$blogInvoegQuery);

// haal het laatste id op
$id = mysqli_insert_id($mysqli);

// voeg de foto toe aan de images map
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){

    // controller het bestand type
    if($_FILES['foto']['type'] == "image/jpg" ||
        $_FILES['foto']['type'] == "image/jpeg" ||
        $_FILES['foto']['type'] == "image/png" ){

        // kijk welke het is
        if($_FILES['foto']['type'] == "image/jpg"){
            $fotoinput = $id . ".jpg";
        } else if($_FILES['foto']['type'] == "image/jpeg"){
            $fotoinput = $id . ".jpeg";
        } else if($_FILES['foto']['type'] == "image/png"){
            $fotoinput = $id . ".png";
        }

        // wat is de fysieke plek
        $map = __DIR__ . "/images/";

        // verplaats de upload naar de juiste map met de juist naam
        move_uploaded_file($_FILES['foto']['tmp_name'], $map . $fotoinput);
    }
}

// voeg de foto toe aan de blog
$fotoUpdateQuery = "UPDATE `blogs` SET `FotoLink` = '$fotoinput' WHERE `blogs`.`ID` = $id";

// voer de query uit
mysqli_query($mysqli,$fotoUpdateQuery);

// voeg alle tags toe


$str = json_decode($_POST['rollen'], true);

foreach ($tags as $item){
    // haal het id op van de tag
    $tagIDQuery = "SELECT `ID` FROM `tags` WHERE `TagNaam` = '$item'";
    // voer de id query uit
    $tagIDQueryResult = mysqli_query($mysqli,$tagIDQuery);
    $tagID = mysqli_fetch_array($tagIDQueryResult);

    echo $tagID;

    $tagQuery = "INSERT INTO `blog_tag_verband` (`BlogID`, `TagID`) VALUES ('$id', '$tagID')";

    //voer de tag query uit
    //mysqli_query($mysqli,$tagQuery);
}

foreach ($str as $item){

    echo $item;
    // haal het id op van de tag
    $tagIDQuery = "SELECT `ID` FROM `tags` WHERE `TagNaam` = '$item'";
    // voer de id query uit
    $tagIDQueryResult = mysqli_query($mysqli,$tagIDQuery);
    $tagID = mysqli_fetch_array($tagIDQueryResult);

    $tag = $tagID['ID'];

    $tagQuery = "INSERT INTO `blog_tag_verband` (`BlogID`, `TagID`) VALUES ('$id', '$tag')";

    //voer de tag query uit
    mysqli_query($mysqli,$tagQuery);
}

header("location:blogs.php");




?>