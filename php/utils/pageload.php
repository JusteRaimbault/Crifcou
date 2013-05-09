<?php
    session_start();
    require 'classes.php';
    require 'indexFonctions.php';
    require 'generalFonctions.php';
    $currentPage = getCurrentPage();

    //update title
    //old method didn't work because the object was created during pageload, so jquery couldn't handle it. It has to exist BEFORE! (?)
    jscall("setTitle(\"CRIFCOU - ".$currentPage->title."\")");

    //check permissions
    if($currentPage->authorized=="ALL"){include "../contenu/$currentPage->name.php";}



    else if($currentPage->authorized=="ROOT"){

        //login zone if not connected as root
        //why doesn't work when the function is loaded at the beginning? because the element doesn't exist, seems to not check again when document is modified
        //(or linked to the level of call??)
        jscall("managelogin");


        if(!isset($_SESSION['root'])) echo <<<END
           <h1>Acces interdit</h1>
           <h2>Vous n'êtes pas administrateur!</h2>
           <p>
            Se connecter :
             <form id='loginForm' action='#'>
             <input type='text' placeholder='Login' id='login' name='login'/>
             <input type='password' placeholder='Mot de passe' id='password' name='password'/>
             <input type='submit' value='Connexion'/>

             </form>

           </p>

END;
        else{
            echo "Vous êtes connecté comme administrateur. <button id='logout'>Déconnexion</button></p>";
            include "../contenu/$currentPage->name.php";

            }
    }
    
?>
