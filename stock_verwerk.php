<?php
// voeg de config en sessie toe
require "session.inc.php";
require "config.inc.php";

// haal de gegevens op uit de input velden
$stockID = htmlentities(base64_decode($_POST['itemName']));
$buyAmount = htmlentities($_POST['stock_buy_amount']);

// maak een query voor het ophalen van alle gegevens die nodig zijn
$stockQuery = "SELECT `stock`.`ItemAmount`,`stock`.`Price`,`stock_houder`.`Cash`, `stock_houder`.`Itembought`, 
`stock_houder`.`ItemAmount` AS TotalItem, `stock_houder`.`IvesteerPrijs`
FROM `stock`,`stock_houder` 
WHERE `stock`.`ID` = $stockID AND `stock_houder`.`PersoonsID` = $session_id";

// voer de query uit en zet dat in een variable
$stockQueryResult = mysqli_query($mysqli,$stockQuery);

// haal de gegevens op
$stockResult = mysqli_fetch_array($stockQueryResult);

// zet alle gegevens die opgehaald zijn in variable
$yourCash = $stockResult['Cash'];
$stockLeft = $stockResult['ItemAmount'];
$stockPrice = $stockResult['Price'];

// reken uit hoeveel stock er nog over is na het inkopen en wat de total prijs is
// reken uit hoeveel geld je nog overhebt als je hebt ingekocht
$totalStockLeft = $stockLeft - $buyAmount;
$totalPrice = $stockPrice * $buyAmount;
$yourCashLeft = $yourCash - $totalPrice ;

// kijk of de persoon niet meer dan stock total koopt en niet minder dan 1
// kijk of de betaal prijs niet groter is dan het geld dat de persoon heeft
if($buyAmount > $stockLeft){
    header("location:wallie_stock.php?msg=2");
    exit();
} else if($buyAmount < 1){
    header("location:wallie_stock.php?msg=5");
    exit();
} else if($totalPrice > $yourCash){
    header("location:wallie_stock.php?msg=1");
    exit();
}

$buyAmount = $stockResult['TotalItem'] + $buyAmount;
$totalPrice = $totalPrice  + $stockResult['IvesteerPrijs'];
// update ook de tijdstip naar nu
$datum = date_create('+2 hour')->format('Y-m-d G');

// maak een query voor het invoeren van je stock
$stockConfirmQuery = "UPDATE stock_houder SET `Cash` = '$yourCashLeft', `ItemBought` = '$stockID', `ItemAmount` = '$buyAmount', `IvesteerPrijs` = '$totalPrice' WHERE `stock_houder`.`PersoonsID` = '$session_id'";

mysqli_query($mysqli, "UPDATE `stock_houder` SET `LastActive` = '$datum'WHERE `stock_houder`.`PersoonsID` = $session_id");
// voer bijde querys uit
mysqli_query($mysqli,$stockConfirmQuery);
mysqli_query($mysqli, "UPDATE `stock` SET `ItemAmount` = '$totalStockLeft', `RefreshDate` = '$datum' WHERE `stock`.`ID` = $stockID");

// stuur de persoon terug naar de market
header("location:wallie_stock.php?msg=3");

?>