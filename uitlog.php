<?php
// include de sessie
require "session.inc.php";

// unset het session['status'] variabel
unset($_SESSION['status']);

// destroy de sessie
session_destroy();

// link naar de inlogpagina
header('location:index.php');
exit();