<?php
session_start();

//write in xml doc the new Entry, after verifying if captcha has the good value
//document entries.xml is supposed to exist
if(isset($_POST['captcha'])&&isset($_SESSION['captcha'])&&$_POST['captcha']==$_SESSION['captcha']){

    require 'datas/entryData.php';

    /*$runners = $_POST['runners'];
    var_dump($runners);*/


    $nom = strtoupper($_POST['nom']);
    $prenom = ucwords(strtolower($_POST['prenom']));
    $sexe = $_POST['sexe'];
    $circuit = $_POST['circuit'];
    $club = $_POST['club'];

    //add the entry
    newEntry($nom, $prenom, $sexe, $circuit,$club);

    echo "Votre inscription a été enregistrée avec succès!\nPour la modifier ou la supprimer veuillez contacter l'administrateur.";

}
else{
    echo "Mauvais valeur du captcha! You are not a human being you monster!";
}



?>
