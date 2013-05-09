<?php
    session_start();
    if(isset($_SESSION['login'])){
            echo "<p>Bonjour ".$_SESSION['login']."  ";
            echo "<button id='logout'>Déconnexion</button></p>";
        }
        else{
             echo "<form id='loginForm' action='#'>  ";
             echo "<input type='text' placeholder='Login' id='login' name='login'/>  ";
             echo "<input type='password' placeholder='Mot de passe' id='password' name='password'/>  ";
             echo "<input type=submit value='Connexion'/>";
             
             echo "</form>";
             echo "<a href=\"#\" id=\"inscription\">S'inscrire</a>";
        }

?>
