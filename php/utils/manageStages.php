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
     * Change current stage number
     *
     */
    if($_POST['action']=="changeCurrentStageNumber"){

        require 'datas/parametersData.php';

        $success = setParameter("currentStageNumber", $_POST['newStageNumber']);

        if($success) echo "Modifications opérées avec succès!";
        else echo "Une erreur est survenue lors des modifications!";

    }



    /*
     * Create new stage
     */
    else if ($_POST['action']=='createNewStage'){

        require 'datas/stagesData.php';
        $number = $_POST['number'];
        $annonceAvailable = FALSE;
        $mapAvailable = FALSE;

        //upload annonce
        if (!empty($_FILES['fichierAnnonce']['tmp_name']) && is_uploaded_file($_FILES['fichierAnnonce']['tmp_name'])) {
        $allowedExtensions = array("pdf");
            if (in_array(end(explode(".",$_FILES['fichierAnnonce']['name'])), $allowedExtensions)){//verification file supported
                //the admin is not supposed to try to inject in file name, so no pb
                $filePath="../../docs/annonce/annonce_".$number.".pdf";
                $annonceAvailable = move_uploaded_file($_FILES['fichierAnnonce']['tmp_name'],$filePath);
             }
        }

        //upload map
        if (!empty($_FILES['fichierMap']['tmp_name']) && is_uploaded_file($_FILES['fichierMap']['tmp_name'])) {
        $allowedExtensions = array("bmp","dib","jpeg","jpg","JPG","jpe","png","pbm","pgm","ppm","sr","ras","tiff", "tif","gif");
        $extension = end(explode(".",$_FILES['fichierMap']['name']));
            if (in_array($extension, $allowedExtensions)){//verification file supported
                //the admin is not supposed to try to inject in file name, so no pb
                $filePath="../../docs/mapSample/mapSample_".$_POST['number'].".".$extension;
                $mapAvailable = move_uploaded_file($_FILES['fichierMap']['tmp_name'],$filePath);         
             }
        }

        //create the new stage
        newStage(intval($number));

        //set its attribute
        
        if($annonceAvailable)setDocStatuts("annonceAvailable", $number, "1");else setDocStatuts("annonceAvailable", $number, "");
        if($mapAvailable)setDocStatuts("mapSampleAvailable", $number, "1"); else setDocStatuts("mapSampleAvailable", $number, "");
    }


    /**
     * Upload doc
     */
    else if ($_POST['action']=='uploadDoc'){

        require 'datas/stagesData.php';
        $number = $_POST['stageNumber'];
        $doctype = $_POST['docType'];
        $docAvailable = FALSE;

        if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {

        $allowedExtensions = array();
        if($doctype=="annonce")$allowedExtensions = array("pdf");
        if($doctype=="mapSample")$allowedExtensions = array("bmp","dib","jpeg","jpg","JPG","jpe","png","pbm","pgm","ppm","sr","ras","tiff", "tif","gif");
        if($doctype=="results")$allowedExtensions = array("html","htm");
        if($doctype=="resultsSI")$allowedExtensions = array("html","htm");
        if($doctype=="resultsCSV")$allowedExtensions = array("csv");

        
        //in case multiple extension, need to delete old files because can not overwrite
        /*if($doctype=="mapSample")foreach(scandir("../../docs/mapSample") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}
        if($doctype=="results")foreach(scandir("../../docs/results") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}
        if($doctype=="resultsSI")foreach(scandir("../../docs/resultsSI") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}
        if($doctype=="resultsCSV")foreach(scandir("../../docs/resultsSI") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}*/
        //delete existing files because doesn't overwrite
        foreach(scandir("../../docs/$doctype") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}


        //var_dump($allowedExtensions);
        
        $extension = end(explode(".",$_FILES['file']['name']));
        echo $extension;
            if (in_array($extension, $allowedExtensions)){//verification file supported
                //the admin is not supposed to try to inject in file name, so no pb
                $filePath="../../docs/$doctype/$doctype"."_"."$number.$extension";
                echo $filePath;
                $docAvailable = move_uploaded_file($_FILES['file']['tmp_name'],$filePath);
             }
        }

        if($docAvailable)setDocStatuts($doctype."Available", $number, "1");else setDocStatuts($doctype."Available", $number, "");
        
        echo "    <p id=\"testuploadDoc\">bouboubou</p>";
    }

    
    
    
    /**
     * Delete doc
     */
    else if ($_POST['action']=='deleteDoc'){

        require 'datas/stagesData.php';
        $number = $_POST['stageNumber'];
        $doctype = $_POST['docType'];
        
        //setting new doc status
        setDocStatuts($doctype."Available", $number, "");

        foreach(scandir("../../docs/$doctype") as $sample){if(strstr($sample, (string) $number)!=FALSE)unlink("../../docs/".$doctype."/".$doctype."_".$number.strstr($sample,"."));}

        
    }
    
    
    
    
    

    /**
     * Delete Stage
     *
     */

    else if ($_POST['action']=='deleteStage'){
         require 'datas/stagesData.php';
         require 'datas/parametersData.php';

         $n = $_POST['stageNumber'];
         $season = intval(getParameter("currentSeason"));

         //delete in database
         deleteStage($n);

         //archive and delete corresponding files
         if(!is_dir("../../docs/archive/$season")){
             mkdir("../../docs/archive/$season");
             mkdir("../../docs/archive/$season/annonce");
             mkdir("../../docs/archive/$season/mapSample");
             mkdir("../../docs/archive/$season/results");
             mkdir("../../docs/archive/$season/resultsSI");
             mkdir("../../docs/archive/$season/resultsCSV");

         }
         rename("../../docs/annonce/annonce_$n.pdf","../../docs/archive/$season/annonce/annonce_$n.pdf");
         //get extensions? :( scan dir and test if contains stage number
         $samples = scandir('../../docs/mapSample'); foreach($samples as $sample){if(strstr($sample, (string) $n)!=FALSE) $ext =  strstr($sample,".");}
         rename("../../docs/mapSample/mapSample_$n".$ext,"../../docs/archive/$season/mapSample/mapSample_$n".$ext);
         $samples = scandir('../../docs/results'); foreach($samples as $sample){if(strstr($sample, (string) $n)!=FALSE) $ext =  strstr($sample,".");}
         rename("../../docs/results/results_$n".$ext,"../../docs/archive/$season/results/results_$n".$ext);
         $samples = scandir('../../docs/resultsSI'); foreach($samples as $sample){if(strstr($sample, (string) $n)!=FALSE) $ext =  strstr($sample,".");}
         rename("../../docs/resultsSI/resultsSI_$n".$ext,"../../docs/archive/$season/resultsSI/resultsSI_$n".$ext);
         
         rename("../../docs/resultsCSV/resultsCSV_$n.csv","../../docs/archive/$season/resultsCSV/resultsCSV_$n.csv");


    }


}

?>
