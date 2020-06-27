<?php
// start de sessie
session_start();
// require de config
require "config.inc.php";

if(isset($_POST['submit'])){
    // haal de gegevens op van form
    $email = htmlentities(mysqli_real_escape_string($mysqli, $_POST['email']));
    $password = htmlentities(mysqli_real_escape_string($mysqli, $_POST['wachtwoord']));

    // kijk of ze allebij langer dan 0 zijn
    if(strlen($email) > 0 && strlen($password) > 0 ){

        // encode het wachtwoord
        $password = md5($password);

        // maak een query
        $inlogQuery = "SELECT `persoonsgegevens`.`ID`, `persoonsgegevens`.`Voornaam`, `persoonsgegevens`.`Achternaam`,`persoonsgegevens`.`Wachtwoord`,
                `persoonsgegevens`.`Email`,`persoonsgegevens`.`Kleur`,`rollen`.`RolNaam`
                FROM `persoonsgegevens` 
                INNER JOIN `rollen` ON `persoonsgegevens`.`RollID` = `rollen`.`ID`
                WHERE `persoonsgegevens`.`Email` = '$email' AND 
                `persoonsgegevens`.`Wachtwoord` = '$password';";



        // voer de query uit
        $inlogQueryResult = mysqli_query($mysqli, $inlogQuery);

        if(mysqli_num_rows($inlogQueryResult) > 0){

            $result = mysqli_fetch_array($inlogQueryResult);

            // sla de gegevens op in de sessie
            $_SESSION['ID']         = $result['ID'];
            $_SESSION['voornaam']   = $result['Voornaam'];
            $_SESSION['achternaam'] = $result['Achternaam'];
            $_SESSION['email']      = $result['Email'];
            $_SESSION['Kleur']      = $result['Kleur'];
            $_SESSION['rol']        = $result['RolNaam'];
            $_SESSION['status']     = 'actief';

            if($result['RolNaam'] != "Colleague" || $result['RolNaam'] != "Teacher"){
                header("location:wallie_stock.php");
            }else{
                header("location:blogs.php");
            }



        } else {
            header("location:index.php?msg=1&email=$email");
            exit();
        }

    } else{
        // gegevens zijn leeg
        header("location:index.php?msg=2&email=$email");
        exit();
    }

}else {
    // formulier is niet ingevuld
    header("location:index.php");
    exit();
}