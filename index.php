<?php
    // include het session bestand
    require_once "session.inc.php";

    // test of er al een sessie bestaat
    if($session_actief){

        // als er al een sessie bestaat:
		// kijk of je een collega of docent bent
		if($session_rol == "Colleague" || $session_rol == "Teacher"){
			// redirect naar de homepagina
			header("location:blogs.php");
			exit();
		} else{
			// redirect naar de stock
			header("location:wallie_stock.php");
			exit();
		}
        

    }

    // haal eventuele GET gegevens op
    $msg = $_GET['msg'];
    $email = $_GET['email'];
    $color = $_GET['color'];
    $firstName = $_GET['first'];
    $lastName = $_GET['last'];

    // kijk of de color is ingevuld
    if (isset($color)) {
        $color = $_GET['color'];
    } else {
        $color = "#ff0000";
    }

?>
<!doctype html>
<html>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/portefolio.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script></head>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="images/favicon-96x96.png" type="image/png" sizes="92x92">
    <title>user login</title>
</head>

<body>

<?php if (isset($_GET['msg'])) { ?>


        <?php

        if($msg == 10){
            echo "<div class='mt-4 shadow alert alert-success alert-dismissible fade show notificatie' role='alert'>";
        } else{
            echo "<div class='mt-4 shadow alert alert-danger alert-dismissible fade show notificatie' role='alert'>";
        }

        // echo de bijbehorende message
        switch($msg){
            case 1:
                echo "Email of Wachtwoord Incorrect";
                break;
            case 2:
                echo "Niet alle velden zijn ingevuld";
                break;
            case 3:
                echo "Email klopt niet";
                break;
            case 4:
                echo "Voornaam klopt niet";
                break;
            case 5:
                echo "Achternaam klopt niet";
                break;
            case 6:
                echo "Je gekozen kleur is geen kleur";
                break;
            case 7:
                echo "Je account type klopt niet";
                break;
            case 8:
                echo "Je wachtwoorden komen niet overeen";
                break;
            case 9:
                echo "Je komt niet van een form af";
                break;
            case 10:
                echo "Registreren compleet, je kan nu inloggen";
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
<div class="align">
    <img class="logo" src="images/logo.svg">
    <div class="card">
        <div class="head">
            <div></div>
            <a id="login" class="selected" href="#login">Login</a>
            <a id="register" href="#register">Register</a>
            <div></div>
        </div>
        <div class="tabs">

            <form action="inlog_verwerk.php"  method="post">
                <div class="inputs">
                    <div class="input">
                        <input placeholder="Email" type="text" name="email" value="<?php echo $email ?>" required>
                        <img src="images/mail.svg">
                    </div>
                    <div class="input">
                        <input placeholder="Password" type="password" name="wachtwoord" required>
                        <img src="images/pass.svg">
                    </div>
                    <div class="checkbox">
                        <a href="blogs.php" class="text-center">Go back to the blog page</a>
                    </div>
                </div>
                <button name="submit" type="submit"><span>Login</span></button>
            </form>

            <form action="register_verwerk.php" method="post">
                <div class="inputs">
                    <div class="input">
                        <input placeholder="Email" name="email" type="email" value="<?php echo $email ?>" required>
                        <img src="images/mail.svg">
                    </div>
                    <div class="input">
                        <input placeholder="Firstname" name="first" type="text" value="<?php echo $firstName ?>" required>
                        <img src="images/user.svg">
                    </div>
                    <div class="input">
                        <input placeholder="Lastname" name="last" type="text" value="<?php echo $lastName ?>" required>
                        <img src="images/user.svg">
                    </div>
                    <span class="text-white">Select your favoritie color</span><br>
                    <span class="text-danger">Visual tip: Dont use dark colors!</span>
                    <div class="color">
                        <input type="color" name="color" value="<?php echo $color ?>">
                    </div>
                    <div class="input">
                        <select required name="type">
                            <option selected disabled>Fill in a account type...</option>
                            <option value="Docent">Teacher</option>
                            <option value="Student">Student</option>
                            <option value="Collega">Colleague</option>
                            <option value="Ouder">Family</option>
                        </select>
                    </div>
                    <span class="text-danger">(Don't use a password you use for everything)</span>
                    <div class="input">
                        <input placeholder="Password" name="password1" type="password" required>
                        <img src="images/pass.svg">
                    </div>
                    <div class="input">
                        <input placeholder="Password check" name="password2" type="password" required>
                        <img src="images/pass.svg">
                    </div>
                </div>
                <button name="submit"><span id="registerText">Register</span></button>
            </form>
        </div>
    </div>
</div>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="javscript/index.js"></script>
</body>
</html>