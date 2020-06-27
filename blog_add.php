<?php

// include de sessie en config
require "session.inc.php";
require "config.inc.php";

// check of de gebruiker een admin is
if($session_rol != "Admin"){
    header("location:blogs.php");
}

// haal eventuele get gegevens op
$msg            = $_GET['msg'];
$projectNaam    = $_GET['projectNaam'];
$omschrijving   = $_GET['omSchrijving'];

$tagQuery =
    "SELECT
    `tags`.`TagNaam`,`tags`.`Kleur`
FROM `tags`";
// maak een result voor opleidingen
$tagQueryResult = mysqli_query($mysqli, $tagQuery);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blog invoegen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portefolio.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script></head>
<body>
<!-- Header -->
<?php
    include "header.php";
    // require de header
?>

<!-- Main Container -->
<main class="container bg-dark text-white rounded-bottom">

    <!-- De titel bovenaan de container-->
    <h2 class="pa_title">Blog Aanmaken</h2>

    <!-- Begin een form -->
    <form action="project_aanmaak_verwerk.php" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <!-- Begint de linker kolom -->
            <div class="col-lg-6 p-3">
                <div>
                    <!-- Maak een tekstveld voor de projectnaam -->
                    <p class="pa_text">Project Naam:</p>
                    <input type="text" class="form-control pa_text_veld" name="projectNaam" placeholder="Blog Title" value="<?php echo $projectNaam ?>" maxlength="64">
                </div>
                <div>
                    <!-- Maak een textbox voor de beschrijving -->
                    <p class="pa_text">Blog Beschrijving:</p>
                    <textarea rows="8" class="form-control pa_text_veld" name="omschrijving"  placeholder="Opdracht Beschrijving"><?php echo $omschrijving ?></textarea>
                </div>
                <input type="hidden" id="actieveRollen" value="" name="rollen" readonly>
                <div>
                    <p class="pa_text">Foto:</p>
                    <input type="file" id="foto" name="foto">
                </div>
                <div>
                    <p class="pa_text">Style:</p>
                    <select name="style">
                        <option value="Big">Big</option>
                        <option value="medium">Medium</option>
                        <option value="Small">Small</option>
                    </select>
                </div>
            </div>

            <!-- Begint de 2de kolom -->
            <div class="col-lg-6 p-4">

                <!-- dropdown group -->
                <ul class="list-group">

                    <?php
                        while ($tag = mysqli_fetch_array($tagQueryResult)){
                    ?>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <input type="checkbox" class="check" value="<?php echo $tag['TagNaam'] ?>"  aria-label="Checkbox for following text input">
                                </div>
                            </div>
                            <p style="background-color: <?php echo $tag['Kleur'] ?>" class="form-control text-white text-left"><?php echo $tag['TagNaam'] ?></p>
                        </div>
                    <?php
                        }
                    ?>
                <!-- Buttons -->
                <button type="button submit" class="btn btn-success">Opslaan</button>
                <a href="blogs.php" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </form>

    <?php if (isset($_GET['msg'])) { // check of er een msg is ?>
        <!-- Alert Message-->
        <div class="m-4 mb-5 shadow alert alert-danger alert-dismissible fade show fixed-bottom" role="alert">
            <?php

            // echo de bijbehorende message
            switch($msg){
                case 1:
                    echo "De blog naam is leeg of langer dan 64 characters";
                    break;
                case 2;
                    echo "De omschrijving van je blog moet je invullen";
                    break;
                case 3;
                    echo "Je moet minimaal 1 tag door geven";
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

</main>

<!--Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

<!-- tag script -->
<script>
    $(document).ready(function () {
        var tagsArray = [];
        $(".check").click(function () {
            var tag = $(this).val();

            if(tagsArray.includes(tag)){
                tagsArray.splice( tagsArray.indexOf(tag), 1);
            } else {
                tagsArray.push(tag);
            }
            $("#actieveRollen").val(JSON.stringify(tagsArray));
        })
    })

</script>

</body>
</html>
