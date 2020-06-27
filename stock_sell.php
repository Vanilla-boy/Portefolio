<?php

// include de sessie en config
require "session.inc.php";
require "config.inc.php";

$msg = $_GET['msg'];


// maak een query voor de stock houder
$stockHouderQuery = "SELECT `PersoonsID`,`Cash`,`stock`.`ItemName`,`ItemBought`,`stock_houder`.`ItemAmount`,`stock_houder`.`IvesteerPrijs`,`stock`.`Price`,`stock`.`ItemAmount` as stockAmount,`stock_houder`.`Reincarnatie`
                    FROM `stock_houder` 
                    LEFT JOIN `stock` ON `stock`.`ID` = `stock_houder`.`ItemBought`
                    WHERE `stock_houder`.`PersoonsID` = $session_id";

// maak een result voor de stock houder query
$stockHouderQueryResult = mysqli_query($mysqli,$stockHouderQuery);

// zet de result van de stock houder result in een var array
$stockHouder = mysqli_fetch_array($stockHouderQueryResult);

// zet sommige gegevens in var
$cash = $stockHouder['Cash'];

// reken de total prijs uit van de winst of verlies
$totalWinst = $stockHouder['Price'] * $stockHouder['ItemAmount'];

$total = $totalWinst - $stockHouder['IvesteerPrijs'];

$datum = date_create('+2 hour')->format('Y-m-d G');

// check of je een profit hebt van 0
if($total == 0){
    // haal de gegevens op
    $stockID = $stockHouder['ItemBought'];
    $items = $stockHouder['stockAmount'];

    // tel bij de stock van deze item op
    $items = $items + $stockHouder['ItemAmount'];

    // voer de update query uit
    mysqli_query($mysqli, "UPDATE `stock` SET `ItemAmount` = '$items', `RefreshDate` = '$datum' WHERE `stock`.`ID` = $stockID");
} else {
    if($stockHouder['Reincarnatie'] >= 1 && $stockHouder['Reincarnatie'] <= 3){
        $totalWinst = $stockHouder['IvesteerPrijs'] + $total * 2;
    }
    else if($stockHouder['Reincarnatie'] >= 4 && $stockHouder['Reincarnatie'] <= 7){
        $totalWinst = $stockHouder['IvesteerPrijs'] + $total * 3;
    } else if($stockHouder['Reincarnatie'] >= 8){
        $totalWinst = $stockHouder['IvesteerPrijs'] + $total * 4;
    }
}

$cash = $cash + $totalWinst;

// maak een update query
$updateQuery = "UPDATE `stock_houder` SET `Cash` = '$cash', `ItemBought` = NULL, `ItemAmount` = NULL, `IvesteerPrijs` = '' WHERE `stock_houder`.`PersoonsID` = $session_id";

// voer de query uit
mysqli_query($mysqli,$updateQuery);
mysqli_query($mysqli, "UPDATE `stock_houder` SET `LastActive` = '$datum'WHERE `stock_houder`.`PersoonsID` = $session_id");


if(isset($msg)){
    header("location:wallie_stock.php?msg=8");
} else{
    header("location:wallie_stock.php?msg=4");
}


?>