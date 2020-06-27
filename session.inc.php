<?php
/* ========================================================================================================
 * VARIABELEN DIE IN DE SESSIE WORDEN OPGESLAGEN:
 * ========================================================================================================
 *
 * $session_actief      [ true/false ]                              | Status van de sessie
 *                                                                  |
 * $session_id          ['int']                                     | ID van de ingelogde user
 * $session_voornaam    [ 'string' ]                                | Voornaam van de actieve gebruiker
 * $session_achternaam  [ 'string' ]                                | Achternaam van de actieve gebruiker
 * $session_email       [ 'string' ]                                | Email van de actieve gebruiker
 * $session_accounttype [ 'Administrator' / 'Docent' / 'Student' ]  | Accounttype van de actieve gebruiker
 *
 * ======================================================================================================== */

//start de sessie
session_start();

// bestaat er een sessie?
if($_SESSION['status'] == 'actief'){

    // als er een sessie bestaat:

    // zet het sessiestatus variabel naar true
    $session_actief = true;

    // sla de sessiegegevens op in variabelen
    $session_id             = $_SESSION['ID'];
    $session_voornaam       = $_SESSION['voornaam'];
    $session_achternaam     = $_SESSION['achternaam'];
    $session_email          = $_SESSION['email'];
    $session_kleur          = $_SESSION['Kleur'];
    $session_rol            = $_SESSION['rol'];

} else {

    // als er geen sessie bestaat:

    // zet het sessiestatus variabel naar false
    $session_actief = false;

}