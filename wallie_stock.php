<?php

// include de sessie en de config
require "session.inc.php";
require "config.inc.php";

// check of er een sessie actief is,
// is de sessie niet actief stuur terug naar de index
if($session_actief == false){
    header("location:index.php");
    exit();
}

// hier staan de query's
// maak een query voor het ophalen van alle stock en prijzen
$stockQuery = "SELECT `ID`, `ItemName`,`ItemAmount`,`Price`,`RefreshDate`,`Rarity`,`Color` FROM `stock` ORDER BY `stock`.`Rarity` ASC";

// maak een query voor de stock houder
$stockHouderQuery = "SELECT `PersoonsID`,`Cash`,`stock`.`ItemName`,`IvesteerPrijs`,`ItemBought`,`stock_houder`.`ItemAmount`,`stock`.`Price`,`Reincarnatie`,`LastActive`
                    FROM `stock_houder` 
                    LEFT JOIN `stock` ON `stock`.`ID` = `stock_houder`.`ItemBought`
                    WHERE `stock_houder`.`PersoonsID` = $session_id";

// maak een query voor de leaderboard
$leaderboardQuery = "SELECT  `PersoonsID`,`Reincarnatie`, `Cash`,`IvesteerPrijs`, SUM(`Cash` + `IvesteerPrijs`) as TotalCash , `persoonsgegevens`.`Voornaam`,`persoonsgegevens`.`Achternaam`,`persoonsgegevens`.`Kleur`
                    FROM `stock_houder` 
                    INNER JOIN `persoonsgegevens` ON `persoonsgegevens`.`ID` = `stock_houder`.`PersoonsID`
                    GROUP BY `stock_houder`.`PersoonsID`
                    ORDER BY `Reincarnatie` DESC, `TotalCash` DESC";

// hier staan de resultaten van de query's
// maak een result query voor de stock query
$stockQueryResult = mysqli_query($mysqli,$stockQuery);

// maak een result voor de normale top 10, 1 voor top 3 under, top 3 over en top top
$leaderboardQueryResult = mysqli_query($mysqli,$leaderboardQuery);
$leaderboardQueryResult2 = mysqli_query($mysqli,$leaderboardQuery);
$leaderboardQueryResult3 = mysqli_query($mysqli,$leaderboardQuery);
$leaderboardQueryResult4 = mysqli_query($mysqli,$leaderboardQuery);


// maak een result voor de stock houder query
$stockHouderQueryResult = mysqli_query($mysqli,$stockHouderQuery);

// zet de result van de stock houder result in een var array
$stockHouder = mysqli_fetch_array($stockHouderQueryResult);

$msg = $_GET['msg'];

function largeNumber($cash){
    // zet de juiste afkorting van het bedrag erbij
    if ($cash >= 1000 && $cash < 10000) {
        $commaNumber = substr($cash, 1, 1);
        $leader = substr($cash, 0, 1);
        $leader = $leader . "," . $commaNumber . " k";
    }
    else if ($cash >= 10000 && $cash  < 100000) {
        $commaNumber = substr($cash, 2, 1);
        $leader = substr($cash, 0, 2);
        $leader = $leader . "," . $commaNumber . " k";
    }
    else if ($cash >= 100000 && $cash < 1000000) {
        $commaNumber = substr($cash, 3, 1);
        $leader = substr($cash, 0, 3);
        $leader = $leader . "," . $commaNumber . " k";
    }
    else if ($cash >= 1000000 && $cash < 10000000) {
        $commaNumber = substr($cash, 1, 1);
        $leader = substr($cash, 0, 1);
        $leader = $leader . "," . $commaNumber . " mln";
    }
    else if ($cash >= 10000000 && $cash < 100000000) {
        $commaNumber = substr($cash, 2, 1);
        $leader = substr($cash, 0, 2);
        $leader = $leader . "," . $commaNumber . " mln";
    }
    else if ($cash >= 100000000 && $cash < 1000000000) {
        $commaNumber = substr($cash, 3, 1);
        $leader = substr($cash, 0, 3);
        $leader = $leader . "," . $commaNumber . " mln";
    }
    else if ($cash >= 1000000000 && $cash < 10000000000) {
        $commaNumber = substr($cash, 1, 1);
        $leader = substr($cash, 0, 1);
        $leader = $leader . "," . $commaNumber . " bil";
    }
    else if ($cash >= 10000000000 && $cash < 100000000000) {
        $commaNumber = substr($cash, 2, 1);
        $leader = substr($cash, 0, 2);
        $leader = $leader . "," . $commaNumber . " bil";
    }
    else if ($cash >= 100000000000 && $cash < 1000000000000) {
        $commaNumber = substr($cash, 3, 1);
        $leader = substr($cash, 0, 3);
        $leader = $leader . "," . $commaNumber . " bil";
    }else if ($cash >= 1000000000000 && $cash < 10000000000000) {
        $commaNumber = substr($cash, 1, 1);
        $leader = substr($cash, 0, 1);
        $leader = $leader . "," . $commaNumber . " tril";
    }
    else if ($cash >= 10000000000000 && $cash < 100000000000000) {
        $commaNumber = substr($cash, 2, 1);
        $leader = substr($cash, 0, 2);
        $leader = $leader . "," . $commaNumber . " tril";
    }
    else if ($cash >= 100000000000000 && $cash < 1000000000000000) {
        $commaNumber = substr($cash, 3, 1);
        $leader = substr($cash, 0, 3);
        $leader = $leader . "," . $commaNumber . " tril";
    }

    return $leader;
}

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wallie's Market</title>
    <!-- alle stylesheets en scripten -->
    <link rel="icon" href="images/favicon-96x96.png" type="image/png" sizes="92x92">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portefolio.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</head>
<body>

<?php
// include de header aan de pagina
include "header.php";
?>

<h1>Wallie's Market</h1>
<!-- zet een msg op het scherm als de transactie niet gelukt is -->
<?php if (isset($_GET['msg'])) { ?>


<?php
// check of de msg 3, 4 of 7 is
if($msg == 3 || $msg == 4 || $msg == 7){
    echo "<div class='mt-4 shadow alert alert-success alert-dismissible fade show' role='alert'>";
}else{
    echo "<div class='mt-4 shadow alert alert-danger alert-dismissible fade show' role='alert'>";
}

    // echo de bijbehorende message
    switch($msg){
        case 1:
            echo "You don't have enough cash to invest";
        break;
        case 2:
            echo "Your trying to buy more items than the stock has available";
        break;
        case 3:
            echo "Stock successfully bought";
        break;
        case 4:
            echo "Stock successfully Sold";
        break;
        case 5:
            echo "You have to buy a minimum of 1 item";
        break;
        case 6:
            echo "Money is not number";
        break;
        case 7:
            echo "Money added to person";
        break;
        case 8:
            echo "Everything has been sold, you waited to long";
            break;
        default:
            echo $msg;
        break;
    }
    ?>
    <!-- maak een button voor het sluiten van de msg -->
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>

<!-- maak een container voor alle items die bij de stock horen -->
<div class="container-fluid">
    <div class="row">
        <?php
        // check of er een vraag is
        $vraag  = false;
        if($vraag){
        ?>
        <!-- informatie over de prijzing van de producten -->
        <div class="card text-white bg-dark mb-1 col-12">
            <div class="card-body">
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Riddle / Question for € 500</h3>
                <p class="card-text">
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-outline-info" data-toggle="modal"
							data-target="#exampleModalCenter">
						Question
					</button>
                    <?php
                    // check of het de admin is
                    if ($session_rol == "Admin") {
                        ?>
                        
                        <button type="button" class="btn btn-outline-warning" data-toggle="modal"
                                data-target="#exampleModalCenter1">
                            Answer
                        </button>
                        <button type="button" class="btn btn-outline-success" data-toggle="modal"
                                data-target="#exampleModalCenter2">
                            Answer Correct
                        </button>
                        <br>
                        <?php
                    }
                    ?>
                    Only one answer per person
                    <br>
                    If I have my doubts than you will only get one forth of the money
                </p>

            </div>
        </div>
        <!-- Question -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Question</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- zet hier een question -->
                        <h1>
                     		If you bounce a ball will it ever stop bouncing?
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Answer -->
        <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Answer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- zet hier een answer -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Correct -->
        <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Answer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="money_add.php" method="post">
                            <select name="id" id="persoon">
                                <?php

                                // maak een query
                                $listQuery = mysqli_query($mysqli, "SELECT `ID`,`Voornaam` FROM `persoonsgegevens`");
                                // loop door allepersonen heen
                                while($persoon = mysqli_fetch_array($listQuery)){
                                    echo "<option value='" . base64_encode($persoon['ID'])  . "'>" . $persoon['Voornaam'] . "</option>";
                                }
                                ?>
                            </select>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Money to add</span>
                                </div>
                                <input type="number" name="money" class="form-control" aria-describedby="basic-addon1">
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Add the money</span>
                                </div>
                                <input class="btn btn-outline-success" type="submit" value="Add the money">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }

        // een random msg
        // pak een random nummer tussen 1 en 1000
        $randomMsg = rand(1,1000);

        if ($randomMsg == 1 || $randomMsg == 666 || $randomMsg == 420){
            ?>
            <div class="card text-white bg-dark mb-1 col-12">
                <div class="card-body">
                    <h3 class="center" style="color: <?php echo $session_kleur; ?>">
                        <?php
                        switch ($randomMsg){
                        case 1:
                            echo "Someday you will get first place";
                        break;
						case 420:
                            echo "You got the perfect number (420)";
                        break;
                        case 666:
                            echo "Welcome to hell";
                        break;
                        }
                        ?>
                    </h3>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- hier staat de market info van die speler -->

        <div class="card text-white bg-dark mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card-body">
                <h3 class="card-title">Your Market Info</h3>
                <p class="card-text">
                    Cash: € <span style='color:<?php echo $session_kleur ?>'><?php echo $stockHouder['Cash']?></span> <br>
                    Cash invested: € <span style='color:<?php echo $session_kleur ?>'><?php echo $stockHouder['IvesteerPrijs']?></span> <br>
                    Laatste Actie: <span style='color:<?php echo $session_kleur ?>'><?php echo $stockHouder['LastActive']?></span> <br>
                    <?php
                        // check if last active a week smaller then now
                        $datum = date_create()->format('Y-m-d');
                        $lastDate = date("Y-m-d", strtotime('+7 days'. $stockHouder['LastActive'])); // $now + 3 hours

                        if($datum > $lastDate){
                            header("location:stock_sell.php?msg=1");
                        }
                    ?>
                    Item bought:
                    <span style='color:<?php echo $session_kleur ?>'>
                        <?php
                        // check of de persoon iets ingekocht heeft
                        if($stockHouder['ItemBought'] == ""){
                            echo "Invested in nothing";
                        } else{
                            echo $stockHouder['ItemAmount'] . " " . $stockHouder['ItemName'];
                        }
                        ?>
                    </span>
                    <br>
                    <span>
                        Sell advise:
                        <?php
                        // bereken hoeveel verschil er tussen invest en nieuw total is
                        $investPrice = $stockHouder['ItemAmount'] * $stockHouder['Price'];
                        // bereken het verschil
                        $verschilPrijs = $investPrice - $stockHouder['IvesteerPrijs'] ;

                        if($stockHouder['Reincarnatie'] >= 1 && $stockHouder['Reincarnatie'] <= 3){
                            $verschilPrijs = $verschilPrijs * 2;
                        }
						else if($stockHouder['Reincarnatie'] >= 4 && $stockHouder['Reincarnatie'] <= 7){
							$verschilPrijs = $verschilPrijs * 3;
						} else if($stockHouder['Reincarnatie'] >= 8){
							$verschilPrijs = $verschilPrijs * 4;
						}
                        // kijk of er een investeer prijs bekend is
                        if($stockHouder['IvesteerPrijs'] < $investPrice){
                            echo "You will make a <span class='text-success'>Profit of €" . $verschilPrijs .  "</span>";
                        } else if($stockHouder['IvesteerPrijs'] == $investPrice){
                            echo "<span style='color:" . $session_kleur . ";'> You're breaking even</span>";
                        } else{
						
                            echo "You will make a <span class='text-danger'>Loss of €" . $verschilPrijs .  "</span>";
                        }
                        ?>
                    </span>

                    <!-- de market regels van wallie de walrus -->
                    <h3 class="center text-danger">Wallie's Market Rules</h3>
                    <p class="m-0">
                        <li>Only invest in ONE item</li>
                        <li>Stock refreshes each hour</li>
                        <li>Prices depend on rarity</li>
                        <li>Wait to long and everything will be sold!</li>
                    </p>
                    <?php
                    // laat een button zien om je spullen te verkopen
                    if($stockHouder['ItemBought'] != ""){
                        echo '<form action="stock_sell.php"><button type="submit" class="btn btn-outline-danger">Sell Stock</button></form>';
                    }
                    // show a button if you have enough cash and reincarnation level
                    if($stockHouder['Reincarnatie'] == 0  && $stockHouder['Cash'] > 1000000000   ||
                        $stockHouder['Reincarnatie'] == 1  && $stockHouder['Cash'] > 2000000000  ||
                        $stockHouder['Reincarnatie'] == 2  && $stockHouder['Cash'] > 4000000000  ||
                        $stockHouder['Reincarnatie'] == 3  && $stockHouder['Cash'] > 9000000000  ||
                        $stockHouder['Reincarnatie'] == 4  && $stockHouder['Cash'] > 18000000000 ||
                        $stockHouder['Reincarnatie'] == 5  && $stockHouder['Cash'] > 27000000000 ||
                        $stockHouder['Reincarnatie'] == 6  && $stockHouder['Cash'] > 43000000000 ||
                        $stockHouder['Reincarnatie'] == 7  && $stockHouder['Cash'] > 55000000000 ||
                        $stockHouder['Reincarnatie'] == 8  && $stockHouder['Cash'] > 89000000000 ||
                        $stockHouder['Reincarnatie'] == 9  && $stockHouder['Cash'] > 100000000000
                    ){
                        echo '<form action="stock_reincarnate.php" method="post"><button type="submit" name="Submit" value="true" class="btn btn-outline-info">Reincarnate</button></form>';
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- informatie over de prijzing van de producten -->
        <div class="card text-white bg-dark mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card-body">
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Wallie's Market Price Range</h3>
                <p class="card-text  text-left">
                    <!-- de market regels van wallie de walrus -->
                    <!-- common price range -->
                    <span class="float-left" style="color: #9d9d9d">Common: </span>
                    <span class="float-right">€ 2 - € 100</span>
                    <br>
                    <!-- Uncommon price range -->
                    <span class="float-left" style="color: #009933">Uncommon: </span>
                    <span class="float-right">€ 20 - € 500</span>
                    <br>
                    <!-- rare price range -->
                    <span class="float-left" style="color: #ff3385">Rare: </span>
                    <span class="float-right">€ 200 - € 2k</span>
                    <br>
                    <!-- super rare price range -->
                    <span class="float-left" style="color: #e600e6">Super Rare: </span>
                    <span class="float-right">€ 5k - € 20k</span>
                    <br>
                    <!-- legendary price range -->
                    <span class="float-left" style="color: #33d6ff">Legendary: </span>
                    <span class="float-right">€ 50k - € 200k</span>
                    <br>
                    <!-- mystic price range -->
                    <span class="float-left" style="color: #9900cc">Mystic: </span>
                    <span class="float-right">€ 500k - € 2mln</span>
                    <br>
                    <!-- golden price range -->
                    <span  class="float-left" style="color: #d4af37">Golden: </span>
                    <span class="float-right">€ 1bil - € 200bil</span>
                </p>
            </div>
        </div>
        <!-- hier staat de leaderboard van wallies market -->
        <div class="card text-white bg-dark mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card-body">
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Wallie's Market Leaderboard</h3>
                <p class="card-text  text-left">
                    <!-- de market leaderboard -->
                    <?php



                    // maak een var voor de plaats
                    $placing = 0;

                    // loop door de top 5 heen
                    while ($leader = mysqli_fetch_array($leaderboardQueryResult)){



                        // voeg de personen aan de lijst
                        if($placing  < 10){
                            $placing ++;
                            echo '<span class="float-left">'. $placing .':<span  style="color: ' . $leader['Kleur'] . '"> ' . $leader['Voornaam'] . ' ' . $leader['Achternaam'] .' </span></span>';
                            echo '<span class="float-right">€ ' . largeNumber($leader['TotalCash']) . ' (' . $leader['Reincarnatie'] . ')</span><br>';
                        }
                    }
                    ?>
                </p>
            </div>
        </div>
        <div class="card text-white bg-dark mb-1 col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card-body">
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Top 3 Under 100k</h3>
                <!-- info voor under 100k total -->
                <p class="card-text  text-left">
                    <!-- de market leaderboard -->
                    <?php
                    // maak een var voor de plaats
                    $placing = 0;

                    // loop door de top 5 heen
                    while ($leader = mysqli_fetch_array($leaderboardQueryResult2)){

                        // maak een var voor het total cash
                        $totalCash = $leader['TotalCash'];



                        // voeg de personen aan de lijst
                        if($placing  < 3 && $totalCash < 100000 && $leader['Reincarnatie'] == 0){
                            $placing ++;

                            echo '<span class="float-left">'. $placing .':<span  style="color: ' . $leader['Kleur'] . '"> ' . $leader['Voornaam'] . ' ' . $leader['Achternaam'] .' </span></span>';
                            echo '<span class="float-right">€ ' . largeNumber($leader['TotalCash']) . ' (' . $leader['Reincarnatie'] . ')</span><br>';
                        }
                    }
                    ?>
                </p>
                <!-- info under 1mil -->
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Top 3 Under 1mil</h3>
                <p class="card-text  text-left">
                    <!-- de market leaderboard -->
                    <?php
                    // maak een var voor de plaats
                    $placing = 0;

                    // loop door de top 5 heen
                    while ($leader = mysqli_fetch_array($leaderboardQueryResult3)){

                        // maak een var voor het total cash
                        $totalCash = $leader['TotalCash'];



                        // voeg de personen aan de lijst
                        if($placing  < 3 && $totalCash < 1000000 && $leader['Reincarnatie'] == 0){
                        $placing ++;

                        echo '<span class="float-left">'. $placing .':<span  style="color: ' . $leader['Kleur'] . '"> ' . $leader['Voornaam'] . ' ' . $leader['Achternaam'] .' </span></span>';
                        echo '<span class="float-right">€ ' . largeNumber($leader['TotalCash']) . ' (' . $leader['Reincarnatie'] . ')</span><br>';
                        }
                    }
                    ?>
                </p>
                <!--- top van de top -->
                <h3 class="center" style="color: <?php echo $session_kleur; ?>">Top 3 Reincarnated</h3>
                <p class="card-text  text-left">
                    <!-- de market leaderboard -->
                    <?php
                    // maak een var voor de plaats
                    $placing = 0;

                    // loop door de top 5 heen
                    while ($leader = mysqli_fetch_array($leaderboardQueryResult4)){



                    // voeg de personen aan de lijst
                    if($placing  < 3 && $leader['Reincarnatie'] >= 1){
                        $placing ++;

                        echo '<span class="float-left">'. $placing .':<span  style="color: ' . $leader['Kleur'] . '"> ' . $leader['Voornaam'] . ' ' . $leader['Achternaam'] .' </span></span>';
                        echo '<span class="float-right">€ ' . largeNumber($leader['TotalCash']) .' (' . $leader['Reincarnatie'] . ')</span><br>';
                        }
                    }
                    ?>
                </p>
            </div>
        </div>
        <!-- loop door alle items van de stock heen -->
        <?php
        while ($stockItem = mysqli_fetch_array($stockQueryResult)){

            //TODO verbeteren van de stock reprice

            // maak een var aan voor het id van deze stock item
            $itemID = $stockItem['ID'];
            // maak een query voor het tellen van alle items gekocht van deze item
            $itemSumQuery = "SELECT SUM(`ItemAmount`) as ItemTotal FROM `stock_houder` WHERE `stock_houder`.`ItemBought` = $itemID";
            // voer de qeury uit
            $itemSumQueryResult = mysqli_query($mysqli,$itemSumQuery);
            // haal de gegevens op
            $itemResult = mysqli_fetch_array($itemSumQueryResult);

            // maak een datum var aan van vandaag
            $datum = date_create('+1 hour')->format('Y-m-d H:i:s');
            $itemDate = date_create($stockItem['RefreshDate'])->format('Y-m-d H:i:s');

            //            echo $stockItem['RefreshDate'] ."<br>";
            //            echo $datum . "<br>";
            //            echo $itemDate . "<br>";
            //            if ($stockItem['RefreshDate'] < $datum){
            //                echo "yas";
            //            }
            // echos voor het testen van de datum

            // check of de datum van de refresh geweest is
            if($itemDate < $datum ){

                // uitrekenen van de nieuwe bedrag waarde
                // pak een getal tussen 1 en 100
                $randomUpOrDown = rand(1,100);
                // maak een standaard waarde en tel daar alle items van dit product bij op
                $standaardRandom = 40;
                $standaardRandom = $standaardRandom + $itemResult['ItemTotal'];

                // check of de waarde boven 80 is
                if($standaardRandom > 80){
                    $standaardRandom = 80;
                }

                // hier staan de random generators voor het geld bedrag
                // hier staat ook een random generator voor up of down
                $prijsRandom = rand(2,10);
                $prijsRandomOmlaag = rand(2,10);

                // check welk getal het is
                if($randomUpOrDown <= $standaardRandom){
                    // prijs gaat omhoog
                    // check of de prijs nu onder 100 is
                    if($stockItem['Price'] <= 100){
                        $itemPrijs = $stockItem['Price'] / $prijsRandom + $stockItem['Price'];
                    }else{
                        $itemPrijs = $stockItem['Price'] / $prijsRandom + $stockItem['Price'] - $prijsRandom;
                    }

                } else{
                    // prijs gaat omlaag
                    $omlaagBedrag = $stockItem['Price'] / $prijsRandomOmlaag;
                    $itemPrijs = $stockItem['Price']  - $omlaagBedrag - 2;
                }
                // kijk welke rarity item het is.
                // kijk of de stock niet hoger dan x aantal is. en of de prijs min en max en x aantal zijn
                // common
                if($stockItem['Rarity'] == "Common") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 2 || $itemPrijs > 100){
                        $itemPrijs = 10;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 1000;

                }
                // uncommon
                else if($stockItem['Rarity'] == "Uncommon") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 20 || $itemPrijs > 500){
                        $itemPrijs = 30;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 800;
                }
                // rare
                else if($stockItem['Rarity'] == "Rare") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 200 || $itemPrijs > 2000){
                        $itemPrijs = 220;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 500;
                }
                // super rare
                else if($stockItem['Rarity'] == "Super Rare") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 5000 || $itemPrijs > 20000){
                        $itemPrijs = 5493;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 200;
                }
                // legendary
                else if($stockItem['Rarity'] == "Legendary") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 50000 || $itemPrijs > 200000){
                        $itemPrijs = 56950;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 100;
                }
                // mystic
                else if($stockItem['Rarity'] == "Mystic") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 500000 || $itemPrijs > 2000000){
                        $itemPrijs = 569500;
                    }
                    // zet de stock op een x aantal
                        $nieuwStock = 50;
                }
                // golden
                else if($stockItem['Rarity'] == "Gold") {
                    // kijk of de prijs onder een x bedrag is of over een x bedrag
                    if($itemPrijs < 1000000000 || $itemPrijs > 200000000000){
                        $itemPrijs = 1648327618;
                    }
                    // zet de stock op een x aantal
                    if($itemResult['ItemTotal'] == 1){
                        $nieuwStock = 0;
                    } else{
                        $nieuwStock = 1;
                    }

                }



                $nieuwDate = date('Y-m-d H' , strtotime("+2 hour"));

                // voer de query uit
                mysqli_query($mysqli,"UPDATE `stock` SET `stock`.`ItemAmount` = '$nieuwStock', `stock`.`Price` = '$itemPrijs', `stock`.`RefreshDate` = '$nieuwDate' WHERE `stock`.`ID` = '$itemID'");


            }

            ?>


            <!-- hier staan alle card info -->
            <div class="card text-white float-left mb-1 p-0 col-12 col-sm-6 col-md-4 col-l-4 col-xl-3 bg-dark rounded">
                <div class="card-head" style="background-color: <?php echo $stockItem['Color']; ?>; height: 2rem"></div>
                <div class="card-body bg-dark">
                    <p class="card-text  text-left">
                        <!-- een form om door te geven hoeveel items je wilt kopen -->
                        <form action="stock_verwerk.php" method="post">

                            <div class="float-left">
                                Item name: <span style="color:<?php echo $session_kleur ?>"><?php echo $stockItem['ItemName'] ?></span>
                            </div>

                            <br>

                            <div class="float-left">
                                Stock: <span style='color:<?php echo $session_kleur ?>'>
                                <?php
                                if ($stockItem['ItemAmount'] == 0){
                                    echo "<span class='bg-danger text-white rounded'>Out of stock</span>";
                                } else {
                                    echo $stockItem['ItemAmount'];
                                }

                                ?>
                                </span>
                            </div>
                            <br>
                            <div class="float-left">
                                Price: € <span style="color:<?php echo $session_kleur ?>"><?php echo $stockItem['Price'] ?></span>
                            </div>
                            <br>
                            <div class="float-left">
                                Refresh on: <span style="color:<?php echo $session_kleur ?>"><?php echo  $stockItem['RefreshDate']  ?></span>
                            </div>
                            <br>
                            <?php
                            // check of de persoon al iets heeft ingekocht en of het deze item is
                            if($stockHouder['ItemBought'] == "" || $stockHouder['ItemBought'] == $stockItem['ID']){
                            ?>
                            <div class="float-left">
                                <span>Buy amount:</span>
                                <input type="number" class=" text-dark" name="stock_buy_amount" value="<?php echo $stockItem['RefreshDate'] ?>">
                                <input type="hidden" name="itemName" value="<?php echo base64_encode($stockItem['ID']) ?>">
                            </div>
                            <?php
                            }
                            ?>
                            <br>
                            <div>
                                <?php
                                // check of je al ergens in geinvesteerd hebt
                                if($stockHouder['ItemBought'] != "" && $stockItem['ID'] != $stockHouder['ItemBought']){
                                    echo "<span>You have already invested in some stock</span>";
                                } else {
                                    echo '<br><p><input class="center btn btn-outline-danger stock" type="submit" value="Invest"></p>';
                                }
                                ?>
                            </div>
                        </form>
                    </p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>

</body>
</html>