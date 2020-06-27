<?php
    //include de sessie
   require "session.inc.php";
    // include de config
     require "config.inc.php";

// check of de sessie inactief is
if($session_actief == false){
    $session_kleur = "#00b3ca";
}

    if($session_actief == true){

        // kijk welke rol het is en maak daarvoor een query voor de blogs die de persoon kan zien
        if($session_rol != "Collega"){

        $blogQuery = "SELECT
                    `blogs`.`ID`, `blogs`.`OnderwerpNaam`,`blogs`.`Informatie`,`blogs`.`DatumAanmaak`,`blogs`.`FotoLink`,`tags`.`TagNaam`,`blogs`.`Style`
                    FROM `blogs` 
                    INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
                    INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
                    WHERE `tags`.`TagNaam` = 'General' OR `tags`.`TagNaam` = 'Bedrijf' OR `tags`.`TagNaam` = 'School'";
        }

        else if($session_rol == "Collega"){

            $blogQuery = "SELECT
                    `blogs`.`ID`, `blogs`.`OnderwerpNaam`,`blogs`.`Informatie`,`blogs`.`DatumAanmaak`,`blogs`.`FotoLink`,`tags`.`TagNaam`,`blogs`.`Style`
                    FROM `blogs` 
                    INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
                    INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
                    WHERE `tags`.`TagNaam` = 'General' OR `tags`.`TagNaam` = 'Bedrijf'";
        }

        // maak een query voor de stock houder
        $stockHouderQuery = "SELECT `PersoonsID`,`Cash`,`stock`.`ItemName`,`IvesteerPrijs`,`ItemBought`,`stock_houder`.`ItemAmount`,`stock`.`Price`
                            FROM `stock_houder` 
                            LEFT JOIN `stock` ON `stock`.`ID` = `stock_houder`.`ItemBought`
                            WHERE `stock_houder`.`PersoonsID` = $session_id";

        // maak een result voor de stock houder query
        $stockHouderQueryResult = mysqli_query($mysqli,$stockHouderQuery);

        // zet de result van de stock houder result in een var array
        $stockHouder = mysqli_fetch_array($stockHouderQueryResult);


        // check of de persoon zonder cash zit
        if($stockHouder['Cash'] <= 0){
            mysqli_query($mysqli, "UPDATE `stock_houder` SET `Cash` = '25' WHERE `stock_houder`.`PersoonsID` = $session_id");
        }




    } else{
        $blogQuery = "SELECT
                    `blogs`.`ID`, `blogs`.`OnderwerpNaam`,`blogs`.`Informatie`,`blogs`.`DatumAanmaak`,`blogs`.`FotoLink`,`tags`.`TagNaam`,`blogs`.`Style`
                    FROM `blogs` 
                    INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
                    INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
                    WHERE `tags`.`TagNaam` = 'General';";
    }
    // haal de results op
    $blogQueryResult = mysqli_query($mysqli, $blogQuery);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width">
    <title>Portfolio</title>
    <link rel="icon" href="images/favicon-96x96.png" type="image/png" sizes="92x92">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portefolio.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script></head>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "dark2", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "Skill set"
                },
                axisY: {
                    title: "Knowledge in %"
                },
                data: [{
                    type: "column",
                    dataPoints: [
                        { y: 90, label: "html" },
                        { y: 85,  label: "CSS" },
                        { y: 85,  label: "Sql" },
                        { y: 65,  label: "PHP" },
                        { y: 50,  label: "Ajax" },
                        { y: 40,  label: "JavaScript" },
                        { y: 42,  label: "Jquery" },
                        { y: 35, label: "C#" },
                        { y: 10,  label: "Scss" }
                    ]
                }]
            });
            chart.render();
        }
    </script>
<body>

<?php
// include de header
    include "header.php";

    // zet de naam van de persoon hier
    ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-5 col-md-4 col-lg-3" id="informatie">

            <h1 class="text-white">Information</h1>
            <?php if($session_actief){ ?>
            <div class="card text-white bg-dark mb-3">
                <div class="card-body">
                    <h3 class="card-title">Your info</h3>
                    <p class="card-text  text-left">
                        Name:  <span style='color: <?php echo $session_kleur ?>'><?php echo $session_voornaam . " " . $session_achternaam ?></span> <br>
                        Email: <span style='color: <?php echo $session_kleur ?>'><?php echo $session_email ?></span> <br>
                        Role:  <span style='color: <?php echo $session_kleur ?>'><?php echo $session_rol ?></span> <br>
                    </p>
                </div>

                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-success">Important</span>
                    <span class="badge badge-pill badge-warning">Extra</span>
                </div>
            </div>

            <?php } ?>

            <!-- info about me -->
            <div class="card text-white bg-ice mb-3">
                <div class="card-header bg-dark"><h3>About me</h3></div>
                <div class="card-body">
                    <p class="text-left">
                        Name: <span class="text-overig">Brian van Niel</span> <br>
                        Birthday: <span class="text-overig">26/11/2001</span> <br>
                        Age:    <span class="text-overig">18 Years</span> <br>
                        Living in: <span class="text-overig">Zoetermeer / Benthuizen</span>
                    </p>

                </div>
                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-success">Important</span>
                    <span class="badge badge-pill badge-info">About me</span>
                </div>
            </div>

            <!-- my skills -->
            <div class="card text-white bg-ice mb-3">
                <div class="card-header bg-dark"><h3>My skill set</h3></div>
                <div class="card-body rounded-lg">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                </div>
                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-success">Important</span>
                    <span class="badge badge-pill badge-info">About me</span>
                </div>
            </div>
            <!-- websites -->
            <div class="card text-white bg-ice mb-3">
                <div class="card-header bg-dark"><h3>Websites I worked on</h3></div>
                <div class="card-body">
                    <p class="text-left">
                        1: <a class="text-overig" href="https://sixicous.ict-lab.nl/index.php">Sixicous</a> <br>
                        2: <a class="text-overig" href="https://ouderbc8.ict-lab.nl/Ouderavond/">Ouderavond</a> <br>
                        3: <a class="text-overig" href="https://mdtbc8.ict-lab.nl/">Multi Diciplinaire teams</a> <br>
                    </p>

                </div>
                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-warning">Fun facts</span>
                    <span class="badge badge-pill badge-danger">School</span>
                    <span class="badge badge-pill badge-info">About me</span>
                </div>
            </div>

            <!-- leerdoelen -->
            <div class="card text-white bg-ice mb-3">
                <div class="card-header bg-dark"><h3>learning goals</h3></div>
                <div class="card-body">
                    <p class="text-left">
                        1: I wanna know more Javascript <br>
                        2: I wanna know more Jquery  <br>
                        3: I wanna be beter in communication<br>
                        4: I want to deepen myself in php
                    </p>

                </div>
                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-warning">Fun facts</span>
                    <span class="badge badge-pill badge-danger">School</span>
                </div>
            </div>
            <!-- free time -->
            <div class="card text-white bg-ice mb-3">
                <div class="card-header bg-dark"><h3>My hobby's</h3></div>
                <div class="card-body">
                    <p class="text-left">
                        1: Archery <br>
                        2: Sleeping <br>
                        3: Eating <br>
                        4: Watching Youtube <br>
                        5: Wathing Anime <br>
                        6: Gaming
                    </p>

                </div>
                <div class="card-footer bg-dark text-right">
                    <span class="badge badge-pill badge-warning">Fun facts</span>
                    <span class="badge badge-pill badge-info">About me</span>
                </div>
            </div>



        </div>
        <!-- maak een container voor de blogs -->
        <div class="col-12 col-sm-7 col-md-8 col-lg-9" id="blog">
            <h1>Blogs</h1>
            <div class="container">
                <div class="row">
                    <?php
                        //Loop door alle blogs heen die in het database zitten
                        while($blog = mysqli_fetch_array($blogQueryResult)) {

                            $omschrijving = $blog['Informatie'];
                            // zet de blog id om in een var
                            $blogID = $blog['ID'];

                            $blogIDLink = base64_encode($blogID);

                            if (strlen($omschrijving) > 100 && $blog['Style'] == "Small") {
                                $omschrijving = substr($omschrijving, 0, 100);
                                $omschrijving = $omschrijving . "... <a style='color: $session_kleur' href='blog_info.php?id=$blogIDLink'>More info</a>";
                            } else if (strlen($omschrijving) > 200 && $blog['Style'] == "medium") {
                                $omschrijving = substr($omschrijving, 0, 200);
                                $omschrijving = $omschrijving . "... <a style='color: $session_kleur' href='blog_info.php?id=$blogIDLink'>More info</a>";
                            } else if (strlen($omschrijving) > 300 && $blog['Style'] == "Big") {
                                $omschrijving = substr($omschrijving, 0, 300);
                                $omschrijving = $omschrijving . "... <a style='color: $session_kleur' href='blog_info.php?id=$blogIDLink'>More info</a>";
                            }

                            // maak een tag query
                            $tagQuery = "SELECT `tags`.`TagNaam`,`tags`.`Kleur`
                                                FROM `blogs` 
                                                INNER JOIN `blog_tag_verband` ON `blogs`.`ID` = `blog_tag_verband`.`BlogID`
                                                INNER JOIN `tags` ON `blog_tag_verband`.`TagID` = `tags`.`ID`
                                                WHERE `blogs`.`ID` = $blogID";

                            // haal de result op
                            $tagQueryResult = mysqli_query($mysqli, $tagQuery);

                            // als de style van de kaart groot is
                            if($blog['Style'] == "Big"){
                    ?>
                        <div class="col-lg-9 col-xl-9 mb-2 d-flex flex-column">
                            <div class="card bg-dark flex-fill">
                                <div class="row no-gutters flex-fill">
                                    <div class="col-md-6 col-lg-6 col-xl-4">
                                        <a href="blog_info.php?id=<?php echo $blogIDLink;?>">
                                            <img src="images/<?php echo $blog['FotoLink'] ?>" class="card-img" style="height:100%; object-fit: cover" alt="...">
                                        </a>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-xl-8">
                                        <div class="card-body">
                                            <h5 class="card-title"><a class="text-white" href="blog_info.php?id=<?php echo $blogIDLink;?>"><?php echo $blog['OnderwerpNaam'] ?></a></h5>
                                            <p class="card-text"><?php echo $omschrijving; ?>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Posted on: <span style="color: <?php echo $session_kleur ; ?>"><?php echo $blog['DatumAanmaak'] ?></span>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Loop door de tags heen in de footer -->
                                <div class="card-footer bg-dark text-right">
                                    <?php
                                    // loop door alle tags heen van deze blog
                                    while($tag = mysqli_fetch_array($tagQueryResult)){
                                        echo ' <span class="badge badge-pill" style="background-color: ' . $tag['Kleur'] .'">' . $tag["TagNaam"] . '</span> ';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        } elseif ($blog['Style'] == "medium") {
                    ?>
                        <div class="col-lg-6 col-xl-6 mb-2 d-flex flex-column">
                            <div class="card bg-dark flex-fill">
                                <div class="row no-gutters flex-fill">
                                    <div class="col-md-6">
                                        <a href="blog_info.php?id=<?php echo $blogIDLink;?>">
                                            <img src="images/<?php echo $blog['FotoLink'] ?>" class="card-img" style="height:100%; object-fit: cover" alt="...">
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body">
                                            <h5 class="card-title"><a class="text-white" href="blog_info.php?id=<?php echo $blogIDLink;?>"><?php echo $blog['OnderwerpNaam'] ?></a></h5>
                                            <p class="card-text"><?php echo $omschrijving; ?>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Posted on: <span style="color: <?php echo $session_kleur ; ?>"><?php echo $blog['DatumAanmaak'] ?></span>
                                                </small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Loop door de tags heen in de footer -->
                                <div class="card-footer bg-dark text-right">
                                    <?php
                                    // loop door alle tags heen van deze blog
                                    while($tag = mysqli_fetch_array($tagQueryResult)){
                                        echo ' <span class="badge badge-pill" style="background-color: ' . $tag['Kleur'] .'">' . $tag["TagNaam"] . '</span> ';
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        } elseif ($blog['Style'] == "Small"){
                    ?>
                        <div class="col-lg-3 col-xl-3 mb-2 d-flex flex-column">
                            <div class="card bg-dark flex-fill">
                                <div class="row no-gutters flex-fill">
                                    <div>
                                        <a href="blog_info.php?id=<?php echo $blogIDLink;?>">
                                            <img src="images/<?php echo $blog['FotoLink'] ?>" class="card-img" style="height:100%; object-fit: cover" alt="...">
                                        </a>
                                    </div>

                                        <div class="card-body">
                                            <h5 class="card-title"><a class="text-white" href="blog_info.php?id=<?php echo $blogIDLink;?>"><?php echo $blog['OnderwerpNaam'] ?></a></h5>
                                            <p class="card-text"><?php echo $omschrijving; ?>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Posted on: <span style="color: <?php echo $session_kleur ; ?>"><?php echo $blog['DatumAanmaak'] ?></span>
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

                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="javscript/chart.js"></script>

</body>
</html>