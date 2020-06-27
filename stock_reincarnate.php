<?php

// include the session and config
require "session.inc.php";
require "config.inc.php";

echo $_POST['Submit'];

// check if the user comes from the form
if($_POST['Submit']){


// make a query for the stock houder
$stockHouderQuery = "SELECT `PersoonsID`,`Cash`,`IvesteerPrijs`,`ItemBought`,`stock_houder`.`ItemAmount`,`Reincarnatie`
                    FROM `stock_houder` 
                    WHERE `stock_houder`.`PersoonsID` = $session_id";

// make a result query for the stock query
$stockHouderQueryResult = mysqli_query($mysqli,$stockHouderQuery);

// put the result of the stock houder result in a var array
$stockHouder = mysqli_fetch_array($stockHouderQueryResult);

// make a variable for the reincarnation
$reincarnation = $stockHouder['Reincarnatie'] + 1;

//make and run the query for the new reincarnation level
mysqli_query($mysqli,"UPDATE `stock_houder` SET `Cash` = '100', `ItemBought` = NULL, `ItemAmount` = NULL, `IvesteerPrijs` = '0', `Reincarnatie` = '$reincarnation' WHERE `stock_houder`.`PersoonsID` = $session_id");

// set the user back to the page
header("location:wallie_stock.php");

} else{

// set the user back to the page
    header("location:wallie_stock.php");
}

?>