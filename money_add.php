<?php

// voeg de config toe
require "config.inc.php";

// haal de gegevens binnen
$moneyToAdd = $_POST['money'];
$persoonID = base64_decode($_POST['id']);

// kijk of het geld een cijfer is
if(!is_numeric($moneyToAdd)){
    header("location:wallie_stock.php?msg=6");
}
// maak een query voor het ophalen van deze persoons money en voer het uit
$moneyQuery = mysqli_query($mysqli, "SELECT `Cash` FROM `stock_houder` WHERE `PersoonsID` = $persoonID");
$money = mysqli_fetch_array($moneyQuery);

// tel het geld bij elkaar op
$moneyTotal = $money['Cash'] + $moneyToAdd;

// voer de query uit voor het updaten
mysqli_query($mysqli, "UPDATE stock_houder SET `Cash` = '$moneyTotal'WHERE `stock_houder`.`PersoonsID` = '$persoonID'");

// stuur terug
header("location:wallie_stock.php?msg=7");



