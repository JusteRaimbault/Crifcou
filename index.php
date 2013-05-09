<?php


    //utilisation de session
    session_start();
    session_regenerate_id();
    if (!isset($_SESSION['initiated'])) {
        session_name("CrifcouSession");
        $_SESSION['initiated'] = true;
    }

    //inclusion des fonctions
    require 'php/utils/classes.php';
    require 'php/utils/indexFonctions.php';
    require 'php/utils/generalFonctions.php';
    

    //generation de la page

    generateHTMLHeader();
    generateContent();
    generateHTMLFooter();

?>