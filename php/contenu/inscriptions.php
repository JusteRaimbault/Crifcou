<?php

require '../utils/datas/parametersData.php';

//get parameters
$isOpen = (boolean) getParameter("entriesOpen");


//stage number var
$stageNumber = intval(getParameter("currentStageNumber"));

if($isOpen){
    echo "<p>Vous pouvez vous inscrire à l'étape $stageNumber du CRIFCOU.</p>";

    //add date, place, etc

    //echo entry form
    include '../utils/entryForm.php';



    echo <<<END
    <p><a href="#" id="openEntryForm">S'inscrire...</a></p>



    
END;

    //add multiple runner inscription!

    jscall("manageEntryForm");





}
else{
    echo "<p>Les inscriptions ne sont actuellement plus ouvertes pour l'étape $stageNumber du CRIFCOU.</p>";
}

echo "<br/><br/><h2>Liste des inscrits</h2>";
//print out list of entries
loadEntriesList();

?>
