<?php
    require '../utils/datas/stagesData.php';
    $stages = getStagesList();
    arsort($stages);
    echo "<div id=\"etapesUser\">";
    foreach($stages as $stage){
        echo "<h1><a href=\"#\">Etape $stage</a></h1><div class=\"stage\" id=\"stage$stage\">";
        outputStage($stage);
        echo "</div>";
    }
    echo "</div>";
    echo "<script type=\"text/javascript\">$(document).ready(function(){ $(\"#etapesUser\").accordion({
      heightStyle: \"content\"
    });})</script>";
?>



<br/>

