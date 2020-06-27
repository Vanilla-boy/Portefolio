<?php
    // inlude de sessie en de config
    require "session.inc.php";
    require "config.inc.php";

    // check of de sessie inactief is
if($session_actief == false){
    $session_kleur = "#00b3ca";
}

// decode het id uit de url
$blogID = base64_decode($_GET['id']);

// haal de msg uit de url
$msg = $_GET['msg'];

// maak een query om de blogs op te halen
$blogGegevensQuery = "SELECT
                    `blogs`.`ID`, `blogs`.`OnderwerpNaam`,`blogs`.`Informatie`,`blogs`.`DatumAanmaak`,`blogs`.`FotoLink`,`tags`.`TagNaam`,`blogs`.`Style`
                    FROM `blogs` 
                    INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
                    INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
                    WHERE `blogs`.`ID` = '$blogID'";

// maak een tag query
$tagQuery = "SELECT `tags`.`TagNaam`,`tags`.`Kleur`
            FROM `blogs` 
            INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
            INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
            WHERE `blogs`.`ID` = $blogID";

// maak een query voor de reacties bij deze blog
$reactieQuery = "SELECT `reacties`.`ID` AS 'reactieID', `reacties`.`Informatie`,`reacties`.`Datum`,`persoonsgegevens`.`Voornaam`,`persoonsgegevens`.`ID` , `persoonsgegevens`.`Achternaam`,`persoonsgegevens`.`Kleur` FROM `blogs`
INNER JOIN `blog_reactie_verband` ON `blogs`.`ID` = `blog_reactie_verband`.`BlogID`
INNER JOIN `reacties` ON `blog_reactie_verband`.`ReactieID` = `reacties`.`ID`
INNER JOIN `reactie_persoon_verband` ON `reacties`.`ID` = `reactie_persoon_verband`.`ReactieID`
INNER JOIN `persoonsgegevens` ON `reactie_persoon_verband`.`PersoonsID` = `persoonsgegevens`.`ID`
WHERE `blogs`.`ID` = $blogID
ORDER BY `reacties`.`ID` DESC ";

// maak een result aan voor reacties
$reactieQueryResult = mysqli_query($mysqli, $reactieQuery);

// haal de result op
$tagQueryResult = mysqli_query($mysqli, $tagQuery);

// haal het result op
$blogGegevensQueryResult = mysqli_query($mysqli,$blogGegevensQuery);

// haal de gegevens uit de array
$blogGegevens = mysqli_fetch_array($blogGegevensQueryResult);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width">
    <title>Portefolio</title>
    <link rel="icon" href="images/favicon-96x96.png" type="image/png" sizes="92x92">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portefolio.css">
    <link rel="stylesheet" href="css/scrollbar.css">

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://kit.fontawesome.com/46e644ad83.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script></head>
<body>

<?php
// include de header
include "header.php";

?>

<div class="container-fluid">
    <div class="row">
        <div class="mt-4 col-sm-12">
            <div class="card bg-dark ">
                <div class="row no-gutters">
                    <div class="col-12 col-md-5 col-xl-5">
                        <img src="images/<?php echo $blogGegevens['FotoLink'] ?>" class="img-fluid" style="object-fit: cover" alt="...">
                </div>
                    <div class="card-body col-12 col-md-7 col-xl-7 ">
                        <h5 class="card-title"><?php echo $blogGegevens['OnderwerpNaam'] ?></h5>
                        <p class="card-text"><?php echo $blogGegevens['Informatie']; ?></p>
                        <p class="card-text sticky-bottom">
                            <small class="text-muted">
                                Posted on: <span style="color: <?php echo $session_kleur ; ?>"><?php echo $blogGegevens['DatumAanmaak'] ?></span>
                            </small>
                        </p>
                    </div>
                </div>
                <!-- Loop door de tags heen in de footer -->
                <div class="card-footer bg-dark text-right">
                    <?php
                    // loop door alle tags heen van deze blog
                    while($tag = mysqli_fetch_array($tagQueryResult)){
                        echo ' <span class="badge badge-pill" style="background-color: ' . $tag['Kleur'] .'">' . $tag["TagNaam"] . '</span> ';
                    }
                        if($session_rol == "Admin") {
                            echo "<div class='btn' id='delete'></div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        </div>
    </div>

<div class="container-fluid">
    <div class="row">

    <?php
        if($session_actief == true){
    ?>

        <div class="col-12 col-md-4 col-xl-3 mt-4 ">
            <div class="card mb-3 bg-dark">
                <div class="row no-gutters">
                    <div class="card-body">
                        <p class="card-text">
                        <form action="comment_add.php" method="post">
                            <input type="hidden" name="blog" id="blog" readonly value="<?php echo base64_encode($blogID)?>">
                            <div class="form-group">
                                Add a Comment <br>
                                <textarea name="comment" id="comment" maxlength="200" style="width: 100%; max-height: 200px"></textarea>
                                <span id="count_message"></span>
                            </div>
                            <button type="submit" id="add" class="btn btn-ice">Add comment</button>
                        </form>
                        <?php if (isset($_GET['msg'])) {

                            // echo de bijbehorende message
                            switch($msg){
                                case 1:
                                    echo "<div class='mt-4 shadow alert alert-danger alert-dismissible fade show' role='alert'>Je comment is telang";
                                    break;
                                default:
                                    echo $msg;
                                    break;
                            }
                            ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

            <?php } if($session_actief){ ?>
                <div class="col-12 col-md-8 col-xl-9 mt-4" id="comments">
            <?php } else{ ?>
                    <div class="col-12 mt-4">
                <?php
                    }

            while($reactie = mysqli_fetch_array($reactieQueryResult)){

                // maak een var voor de reactie id
                    $reactieID = $reactie['reactieID'];
                // maak een query voor het ophalen van de likes
                    $likeQuery = "SELECT COUNT(`ReactieID`) as likes FROM `reactie_likes` WHERE `ReactieID` = $reactieID";
                // haal de result op van de query
                    $likeQueryResult = mysqli_query($mysqli,$likeQuery);

                // haal de gegevens uit de array
                    $like = mysqli_fetch_array($likeQueryResult);

                // maak een query om te kijken of de persoon deze reactie geliked heeft
                    $persoonLikeQuery = "SELECT  `PersoonsID` FROM `reactie_likes` WHERE `ReactieID` = $reactieID";
                // voer de query uit
                    $persoonLikeQueryResult = mysqli_query($mysqli,$persoonLikeQuery);
                // haal de gegevens uit het array
                    $likePersoon = mysqli_fetch_array($persoonLikeQueryResult);

        ?>
                <!-- reactie cards -->
                <div class="card mb-3 bg-dark" style="border-left: solid 5px <?php echo $reactie['Kleur'] ?>">
                    <div class="row no-gutters">
                        <div class="card-body">

                            <?php
                                // kijk of de sessie actief is
                                if($session_actief){
                                    // kijk of de reactie van de persoon die ingelogd is
                                    if($reactie['ID'] == $session_id) {
                                        ?>
                                        <span class="fas fa-heart float-right like" style="color:<?php echo $session_kleur ?> "> <span class="like_nummer"><?php echo $like['likes'] ?></span></span>

                                        <?php
                                    }
                                    // kijk of de like id gelijk is aan de persoon is die ingelogd is
                                    else if ($likePersoon['PersoonsID'] == $session_id) {
                                        ?>
                                        <a href="like_delete.php?reactieid=<?php echo $reactieID?>&blogid=<?php echo base64_encode($blogID) ?>">
                                            <span class="fas fa-heart float-right like" style="color:<?php echo $session_kleur ?> "> <span class="like_nummer"><?php echo $like['likes'] ?></span></span>
                                        </a>
                                        <?php
                                    }
                                    else {
                                        ?>

                                        <a href="like_verwerk.php?reactieid=<?php echo base64_encode($reactieID) ?>&blogid=<?php echo base64_encode($blogID) ?>">
                                            <span class="far fa-heart float-right like" style="color:<?php echo $session_kleur ?> "> <span class="like_nummer"><?php echo $like['likes'] ?></span></span>
                                        </a>
                                        <?php
                                    }
                                }
                                // persoon is niet ingelogd
                                else{
                                    ?>
                                    <span class="fas fa-heart float-right like" style="color:<?php echo $session_kleur ?> "> <span class="like_nummer"><?php echo $like['likes'] ?></span></span>
                                    <?php
                                }
                            ?>
                            <!-- voeg de informatie toe en de delete knop voor de reactie -->
                            <p class="card-text"><?php echo $reactie['Informatie'] ?>
                                <!-- kijk of de sessie id het zelf is als de reactie persoon id of dat de sessie een admin rol is -->
                                <?php if($session_id == $reactie['ID'] || $session_rol == "Admin"){ ?>
                                    <div class="reactiedel" id="reactiedel">
                                        <a href="reactiedel.php?id=<?php echo $reactie['reactieID'] ?>&blog=<?php echo $blogID ?>" class="float-right btn btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                <?php } ?>

                            <!-- voeg de naam en datum van de reageerder toe -->
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-day" style="color: white;"></i> <span style="color: <?php echo $reactie['Kleur'] ?>"><?php echo $reactie['Datum'] ?> </span>
                                    <i class="fas fa-user" style="color: white;"></i> <span style="color: <?php echo $reactie['Kleur'] ?>"><?php echo $reactie['Voornaam'] . " " . $reactie['Achternaam'] ?></span>
                                </small>
                            </p>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- script voor het deleten van de blog -->
<script src="javscript/functies.js"></script>

</body>
</html>