<?php
session_start();
//idem security check is necessary
if(!isset($_SESSION['root'])){
    include '../../html/onedoesnotsimply.html';
}
else{




    //check action to do
    /**
     *
     * Change Entries Status
     *
     */
    if($_POST['action']=="changeStatuts"){

        require 'datas/parametersData.php';
        
        $success = setParameter("entriesOpen", !((boolean) getParameter("entriesOpen")));

        if($success) echo "Modifications opérées avec succès!";
        else echo "Une erreur est survenue lors des modifications!";

    }



    /*
     * upload entry file
     */
    else if ($_POST['action']=='entryCSVFileUpload'){

        require 'datas/entryData.php';

        if (!empty($_FILES['fichier']['tmp_name']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {

        $allowedExtensions = array("csv","txt");
            if (in_array(end(explode(".",$_FILES['fichier']['name'])), $allowedExtensions)){//verification file supported

                $filePath="../../data/temp/".$_FILES['fichier']['name'];
                if(move_uploaded_file($_FILES['fichier']['tmp_name'],$filePath)){

                    $entries = file($filePath);
                    foreach($entries as $entry){
                        $values = explode(";", $entry);
                        $nom = strtoupper($values[0]);
                        $prenom = ucwords(strtolower($values[1]));
                        $sexe = $values[2];
                        $circuit = $values[3];
                        $club = $values[4];
                        newEntry($nom, $prenom, $sexe, $circuit, $club);
                    }

                    //delete the temporary file
                    $success = (bool) unlink($filePath);
                    //correct bug : can't call jQuery because in iframe.. :(
                    /*if($success) echo "<script tupe=>$(document).ready(function(){loadCurrentPage(\"inscriptions\",\"\");})</script>";
                    else echo "<script>$(document).ready(function(){alert(\"Une erreur est survenue!\");})</script>";*/
                }
             }
        }


    }


    else if ($_POST['action']=='deleteEntries'){

        require 'datas/entryData.php';
        deleteEntries();
        echo "Suppression effectuée";
    }


    //if action is not set, nothing to do here! (a priori not possible, or if root access directly the page)
}

?>
