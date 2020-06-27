<?php

// include de config en de sessie
require "session.inc.php";

require "config.inc.php";

// haal alle gegevens uit het formulier

$email      = htmlentities($_POST['email']);
$firstName  = htmlentities($_POST['first']);
$lastName   = htmlentities($_POST['last']);
$type       = htmlentities($_POST['type']);
$password1  = htmlentities($_POST['password1']);
$password2  = htmlentities($_POST['password2']);
$color      = htmlentities($_POST['color']);

// maak var aan voor checks
$emailCheck = false;
$firstNameCheck = false;
$lastNameCheck = false;
$typeCheck = false;
$passwordCheck = false;
$colorCheck = false;

// check of je van een submit komt
if(isset($_POST['submit'])){

    // check of de email een email is
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailCheck = true;
    } else {
        // stuur terug naar inlog
        header("location:index.php?msg=3&first=$firstName&last=$lastName");
        exit();
    }

    // check of de firstname is ingevuld
    if(isset($firstName) && $firstName != ""){
        $firstNameCheck = true;
    }else{
        // stuur terug naar inlog
        header("location:index.php?msg=4&email=$email&last=$lastName");
        exit();
    }

    // check of de lastname is ingevuld
    if(isset($lastName) && $lastName != ""){
        $lastNameCheck = true;
    }else{
        // stuur terug naar inlog
        header("location:index.php?msg=5&email=$email&first=$firstName&color=$color&type=$type");
        exit();
    }

    // check of de kleur begint met #
    if (substr($color, 0, 1) === '#'){
        $colorCheck = true;
    } else{
        // stuur terug naar inlog
        header("location:index.php?msg=6&email=$email&first=$firstName&last=$lastName");
        exit();
    }

    // check of het account type gelijk is aan 1 van deze drie
    if($type == "Docent" || $type == "Student" || $type == "Collega" || $type == "Ouder"){
        $typeCheck = true;
    }else{
        // stuur terug naar inlog
        header("location:index.php?msg=7&email=$email&first=$firstName&last=$lastName");
        exit();
    }

    // check of de wachtworden gelijk zijn
    if($password1 == $password2){
        $passwordCheck = true;
    }else{
        // stuur terug naar inlog
        header("location:index.php?msg=8&email=$email&first=$firstName&last=$lastName");
        exit();
    }

} else{
    // stuur terug naar de inlog
    header("location:index.php?msg=9");
    exit();
}

if($emailCheck && $typeCheck && $passwordCheck && $firstNameCheck && $lastNameCheck && $colorCheck){
    echo "alles klopt kwa info";

    // kijk welke rol je hebt gekozen
    if($type == "Docent"){
        $rolID = 9;
    } else if($type == "Student"){
        $rolID = 7;
    } else if($type == "Collega"){
        $rolID = 8;
    } else if($type == "Ouder"){
        $rolID = 10;
    }

    // encode het wachtwoord
    $password = md5($password1);

    // maak een query
    $registreerQuery = "INSERT INTO `persoonsgegevens` (`ID`, `Voornaam`, `Achternaam`, `Email`, `Wachtwoord`, `Kleur`, `RollID`)
                        VALUES (NULL, '$firstName', '$lastName', '$email', '$password', '$color', '$rolID')";

    // voer de query uit
    mysqli_query($mysqli , $registreerQuery);


// haal het laatste id op
    $id = mysqli_insert_id($mysqli);


        // voeg deze persoon toe aan de stock houder tabel en geef de persoon een start capitaal
        mysqli_query($mysqli, "INSERT INTO `stock_houder` (`PersoonsID`, `Cash`, `ItemBought`, `ItemAmount`,`ivesteerPrijs`) VALUES ('$id', '100', NULL, NULL,NULL)");



    // verwerk de gegevens en stuur terug naar inlog met een goede msg
    header("location:index.php?msg=10&email=$email");
}

?>