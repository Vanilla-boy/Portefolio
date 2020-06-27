<?php
// start een sessie
session_start();

// kijk of een sessie is gestart
if(isset($_SESSION['secrets'])){
    // tel een secret erbij op
    $_SESSION['secrets'] ++ ;

    if($_SESSION['secrets'] > 5){
        $_SESSION['secrets'] = 0;
    }

    // zet hem terug op null
}

echo $_SESSION['secrets'];
