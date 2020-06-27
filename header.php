
<?php
require "session.inc.php";
?>

<nav class="navbar sticky-top navbar-expand-lg navbar-dark progress-bar-striped progress-bar-animated php">

        <span class="navbar-brand text-white">
            <a class="text-white" href="blogs.php"><img src="images/letter B.png" width="30" height="30" class="secret_button d-inline-block align-top" alt="">rian's Portfolio</a>
        </span>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="true" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse" id="navbarTogglerDemo02" style="">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active" style="font-size: 20px">
                <a class="nav-link" href="blogs.php">Blogs <span class="sr-only">(current)</span></a>
            </li>
            <?php
                //check of de sessie actief is
                if($session_actief && $session_rol != "Teacher" || $session_actief && $session_rol != "Colleague"){
                    echo '<li class="nav-item active" style="font-size: 20px">
                               <a class="nav-link" href="wallie_stock.php">Market <span class="sr-only">(current)</span></a>
                          </li>';
                }
            ?>

            <li class="nav-item active d-block d-lg-none " style="font-size: 20px">
                <!-- check of de sessie niet actief is -->
                <?php if($session_actief == false){ ?>
                    <a class="nav-link text-white" href="index.php">Login</a>
                <?php } else { ?>
                    <a class="nav-link text-white" href="uitlog.php"> Logout</a>
                <?php } ?>
            </li>
            <?php if($session_rol == "Admin"){?>
                <li class="nav-item active" style="font-size: 20px">
                    <a class="nav-link" href="blog_add.php">Add a blog</a>
                </li>
            <?php } ?>
        </ul>

        <form class="form-inline my-2 my-lg-0 d-none d-lg-flex rounded progress-bar-striped progress-bar-animated btn-info" style="font-size: 20px">
                <?php if($session_actief == false){ ?>
                    <a class="nav-link text-white" href="index.php">Login</a>
                <?php } else { ?>
                    <a class="nav-link text-white" href="uitlog.php"> Logout</a>
                <?php } ?>

        </form>
    </div>
</nav>
