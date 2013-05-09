<?php

/**
 * Tells if the given stage already exists.
 *
 * @param <int> $stagenumber
 * @return <boolean>
 */
function isExistingStage($stagenumber){
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $stages = $all->getElementsByTagName("stage");
    $res = FALSE;
    foreach ($stages as $stage){
        if(intval($stage->getAttribute("number"))==$stagenumber)$res=TRUE;
    }
    return $res;
}

/**
 * Creates a new stage in stages list.
 *
 * @param <int> $stagenumber
 */
function newStage($stagenumber){
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $stages = $all->getElementsByTagName("stages")->item(0);
    $newStage = $all->createElement("stage");
    $newStage->setAttribute("number",$stagenumber);
    $newStage->appendChild(new DOMElement("annonceAvailable"));
    $newStage->appendChild(new DOMElement("resultsAvailable"));
    $newStage->appendChild(new DOMElement("resultsSIAvailable"));
    $newStage->appendChild(new DOMElement("mapSampleAvailable"));
    $newStage->appendChild(new DOMElement("resultsCSVAvailable"));
    $stages->appendChild($newStage);

    $all->save("../../data/stages.xml");

}


function deleteStage($stagenumber){
    /**
     * ADD DOCS DELETION ! ?? Yes done in the calling function, archive of the docs
     */


    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $xpath = new DOMXPath ($all);
    $stages = $all->getElementsByTagName("stages")->item(0);
    $stage = $xpath->query('stage[@number="'.$stagenumber.'"]')->item(0);
    $stages->removeChild($stage);
    $all->save("../../data/stages.xml");
}


/**
 *
 * @param <type> $stagenumber
 * @param <type> $newstatut
 */
function setDocStatuts($docType,$stagenumber,$newstatut){
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $stages = $all->getElementsByTagName("stage");
    foreach($stages as $stage){
        if(intval($stage->getAttribute("number"))==$stagenumber){
            $doc = $stage->getElementsByTagName($docType)->item(0);
            $doc->nodeValue = $newstatut;
        }
    }

    $all->save("../../data/stages.xml");
}


function getStagesList(){
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $stages = $all->getElementsByTagName("stage");
    $res = array();
    foreach($stages as $stage){
        $n=intval($stage->getAttribute("number"));
        $res[$n]=$n;
    }
    return $res;
}

function outputStage($number){
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $xpath = new DOMXPath ($all);
    $stages = $all->getElementsByTagName("stage");
    foreach($stages as $stage){
        $n=$stage->getAttribute("number");
        if($n==$number){
            $annonce = (boolean) $xpath->query("annonceAvailable",$stage)->item(0)->nodeValue;
            $map = (boolean) $xpath->query("mapSampleAvailable",$stage)->item(0)->nodeValue;
            $results = (boolean) $xpath->query("resultsAvailable",$stage)->item(0)->nodeValue;
            $resultsSI = (boolean) $xpath->query("resultsSIAvailable",$stage)->item(0)->nodeValue;

            if($map){
                $images = scandir("../../docs/mapSample");
                foreach($images as $image){if(strstr($image, "mapSample_$number"))$file=$image;}
                echo<<<END
                <div style="display:inline-block"><img src="docs/mapSample/$file" height="150"/></div>
END;
            }
            echo "<div style=\"display:inline-block\">";
            if($results)echo<<<END
                <h2>Résultats : </h2>
                
                <p><a id="displayResults$number" href="#">Résultats...</a>

                <iframe bgcolor=#FFFFFF id="results$number" height="100%" width="55%" style="margin-left: 20%" hidden></iframe>

                <script>
                    $("#displayResults$number").click(function(){
                        $("#results$number").attr("src", "docs/results/results_$number.html");
                                $("#results$number").lightbox_me({
                                    centered:true,
                                    overlayCSS:{background: 'black', opacity: .8}
                                 });
                            })
                </script>

END;
            if($resultsSI)echo<<<END
   
                <a id="displayResultsSI$number" href="#">Résultats SI...</a></p>

                <iframe id="resultsSI$number" height="100%" width="60%" style="margin-left: 20%" hidden></iframe>

                <script>
                    $("#displayResultsSI$number").click(function(){
                        $("#resultsSI$number").attr("src", "docs/resultsSI/resultsSI_$number.html");
                                $("#resultsSI$number").lightbox_me({
                                    centered:true,
                                    overlayCSS:{background: 'black', opacity: .8}
                                 });
                            })
                </script>
END;

            if($annonce)echo<<<END
                <h2>Invitation : </h2>

                <p><a id="displayAnnonce$number" href="#">Afficher...</a></p>

                <iframe id="annonce$number" height="100%" width="55%" style="margin-left: 20%" hidden></iframe>

                <script>
                    $("#displayAnnonce$number").click(function(){
                        $("#annonce$number").attr("src", "docs/annonce/annonce_$number.pdf");
                                $("#annonce$number").lightbox_me({
                                    centered:true,
                                    overlayCSS:{background: 'black', opacity: .8},
                                 });
                            })
                </script>
END;
            echo "</div>";
        }
    }
}


function outputStagesList(){
    echo "<table id=\"stagestable\" class=\"dtable\">";
    echo "<thead><tr><th>Numéro</th><th>Annonce</th><th>Map sample</th><th>Résultats</th><th>Résultats SI</th><th>Résultats CSV</th><th>Supprimer</th></tr></thead>".PHP_EOL."<tbody>";
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $xpath = new DOMXPath ($all);
    $stages = $all->getElementsByTagName("stage");
    foreach($stages as $stage){
        $n=$stage->getAttribute("number");
        $annonce = (boolean) $xpath->query("annonceAvailable",$stage)->item(0)->nodeValue;
        $map = (boolean) $xpath->query("mapSampleAvailable",$stage)->item(0)->nodeValue;
        $results = (boolean) $xpath->query("resultsAvailable",$stage)->item(0)->nodeValue;
        $resultsSI = (boolean) $xpath->query("resultsSIAvailable",$stage)->item(0)->nodeValue;
        $resultsCSV = (boolean) $xpath->query("resultsCSVAvailable",$stage)->item(0)->nodeValue;

        echo "<tr><td>$n</td><td>";
        if($annonce) {echo "Ok - ";outputAddDocForm("Remplacer...","annonce", $n,"uploadDoc");outputAddDocForm("Supprimer...","annonce", $n,"deleteDoc");}
        else outputAddDocForm("Ajouter...","annonce", $n,"uploadDoc");

        echo "</td><td>";
        if($map)  {echo "Ok - ";outputAddDocForm("Remplacer...","mapSample", $n,"uploadDoc");outputAddDocForm("Supprimer...","mapSample", $n,"deleteDoc");}
        else outputAddDocForm("Ajouter...","mapSample", $n,"uploadDoc");

        echo "</td><td>";
        if($results) {echo "Ok - ";outputAddDocForm("Remplacer...","results", $n,"uploadDoc");outputAddDocForm("Supprimer...","results", $n,"deleteDoc");}
        else outputAddDocForm("Ajouter...","results", $n,"uploadDoc");
        echo "</td><td>";
        if($resultsSI)  {echo "Ok - ";outputAddDocForm("Remplacer...","resultsSI", $n,"uploadDoc");outputAddDocForm("Supprimer...","resultsSI", $n,"deleteDoc");}
        else outputAddDocForm("Ajouter...","resultsSI", $n,"uploadDoc");
        echo "</td><td>";
        if($resultsCSV) {echo "Ok - ";outputAddDocForm("Remplacer...","resultsCSV", $n,"uploadDoc");outputAddDocForm("Supprimer...","resultsCSV", $n,"deleteDoc");}
        else outputAddDocForm("Ajouter...","resultsCSV", $n,"uploadDoc");
        echo "</td><td><a href=\"#\" class=\"deleteStage\" stageNumber=\"$n\">Supprimer...</a></td></tr>";

    }
    
echo<<<END
    </tbody></table>
    <iframe id="uploadDoc"></iframe>
    <a href="#" id="testLengthiframe">Test</a>
    
    <script type="text/javascript">$(document).ready(function(){
                            
                            
                            $("#testLengthiframe").click(function(){alert($("#uploadDoc").text());});
    
                            //datatable
                            activateDatatable("stagestable");

                            //deletion
                            $(".deleteStage").click(function(){
                                var n = $(this).attr("stageNumber");
                                if(confirm("Confirmez la suppression de l'étape !")){
                                    $.post("php/utils/manageStages.php",{"action":"deleteStage","stageNumber":n},function(){
                                        loadCurrentPage("admin","");
                                    });
                                }
                            });

                            //upload
                            $(".addDocShow").click(function(){
                                if($(this).attr("action")=="deleteDoc"){
                                    var n = $(this).attr("stageNumber");
                                    var type = $(this).attr("doc");
                                    if(confirm("Confirmez la suppression !")){
                                        $.post("php/utils/manageStages.php",{"action":"deleteDoc","stageNumber":n,"docType":type},function(){
                                            loadCurrentPage("admin","");
                                        });
                                }
                                }
                                else{
                                    $("#addDoc"+$(this).attr("doc")+$(this).attr("stageNumber")+$(this).attr("action")).lightbox_me({
                                        centered:true,
                                        overlayCSS:{background: 'black', opacity: .8},
                                        destroyOnClose: true,
                                        onClose: function(){/*loadCurrentPage("admin","");*/}
                                     });
                                }

                            });

    })

                    </script>
        
END;
    


}


function outputAddDocForm($text,$doctype,$n,$action){
    if($action=="deleteDoc") {echo "<a href=\"#\" class=\"addDocShow\" doc=\"$doctype\" stageNumber=\"$n\" action=\"$action\">$text</a>";}
    else{ 
    
       echo<<<END
    
        <a href="#" class="addDocShow" doc="$doctype" stageNumber="$n" action="$action">$text</a>

        <form id="addDoc$doctype$n$action" class="addDoc" action="php/utils/manageStages.php" method="post" enctype="multipart/form-data" target="uploadDoc" onsubmit="closeForm()" hidden>
                        <input type="text" name="action" id="action" value="$action" class="hidden"/>
                        <input type="text" name="docType" id="docType" value="$doctype" class="hidden"/>
                        <input type="text" name="stageNumber" id="stageNumber" value="$n" class="hidden"/>
                        Fichier : <input type="file" name="file" id="file"/><br/>
                        <input type="submit" value="Envoyer" id="uploadButton"/>
            </form>

END;
    }
}




?>
