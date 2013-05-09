
<?php

//need to check also here if user is logged, because intrusion is possible through file path

//javascript navigation is the big danger : big security fail!! (present in old site! not so much because bugged because of requires, but..!)

//javascript function are not in global file, in order not to give admin indications to the random guy!


/**OK SOLVED (forget session_start..)
//PB : SESSION var not considered when ajax calls on utils page
//sol : add a POST var which confirms the origin of the call ; not absolutely secure.. :( FIND SOMETHING ELSE? (with an iframe?)
*/

if(!isset($_SESSION['root'])){
    include '../../html/onedoesnotsimply.html';
}
else{


    echo "<h1>Gestion en ligne du Crifcou</h1><br/><br/>";

    /* *
     *
     * LIST OF ADMIN FEATURES
     *
     *
     */


     /*
      * Entries management
      * 
      */


    //open and close inscriptions
    //manage inscriptions (delete,add runners, etc)-> list of runners as datatable!
    //upload entry file
    //download files (xml inscriptions, csv too, etc)
echo <<<END
    
    <h2>Gestion des inscriptions</h2>

    <p>
        <a href="#" class=adminAreaToggle id="entriesManagement">Afficher...</a>

        <div id="entriesManagementArea" hidden>
            <ul>

                <li>Ouvrir/Fermer les inscriptions : <button id='changeEntriesStatus'>Change</button></li>
    
                <li><a href="data/entries.xml" target="_blank">Télécharger la liste des inscrits (xml IOF)...</a></li>

                <li><a href="#" id="uploadEntryListToggle">Uploader une liste d'inscrits...</a>
                    <div id="uploadEntryList" class="adminActionDiv" hidden>(format csv [Nom;Prenom;Sexe;Circuit;Club]) :
                    <form action="php/utils/manageEntries.php" method="post" enctype="multipart/form-data" target="uploadEntries">
                        <input type="text" name="action" id="action" value="entryCSVFileUpload" hidden/>
                        <input type="file" name="fichier" id="fichier"/>
                        <input type="submit" value="Envoyer" id="uploadButton"/>
                    </form>
                    <iframe id="uploadEntries" hidden></iframe>
                    </div>
                    <script type="text/javascript">$(document).ready(function(){
                            $("#uploadEntryListToggle").click(function(){
                            $("#uploadEntryList").toggle();})})
                    </script>
                </li>


                <li>Effacer la liste des inscrits : <button id='deleteEntries'>Effacer</button></li>



            </ul>
        </div>
    </p>
    
    <script>
    $(document).ready(function(){


    //Entries status
        $("#changeEntriesStatus").click(function(){

            $.post("php/utils/manageEntries.php",{"action":"changeStatuts"},function(rep){
                alert(rep);
            })
        });

        $("#deleteEntries").click(function(){
            if(confirm("Confirmez la suppression de la liste des inscrits !")){
                $.post("php/utils/manageEntries.php",{"action":"deleteEntries"},function(rep){
                    alert(rep);
                })
            }
        });
    })
    </script>

END;


    /*
     * Stages Management
     *
     */

    //set params : current race inscription
    //add race (in link to current race)
    //upload results

    echo <<<END

   <h2>Gestion des étapes</h2>

    <p>
        <a href="#" class=adminAreaToggle id="stagesManagement">Afficher...</a>

        <div id="stagesManagementArea" hidden>
            <ul>
                <li>Changer l'étape courante : <select id="chooseCurrentStage">
                    <option>Choisir...</option>
END;
    require 'datas/stagesData.php';
    require 'datas/parametersData.php';
    $stages = getStagesList();
    $currentStage = intval(getParameter("currentStageNumber"));
    foreach($stages as $stagenumber){echo '<option'; if(intval($stagenumber)==$currentStage) echo ' selected="selected"';echo '>'.$stagenumber.'</option>';}
    echo<<<END
                </select>
                <script type="text/javascript">$(document).ready(function(){
                $("#chooseCurrentStage").change(function(){
                    var n = $(this).val();
                    if(n=="Choisir..."){alert("Veuillez choisir un numéro d'étape!");}
                    else{
                        $.post("php/utils/manageStages.php",{"action":"changeCurrentStageNumber","newStageNumber":n},function(rep){
                             alert(rep);
                         })
                    }
                });})
                </script>
                </li>

                <li><a href="#" id="createStageToggle">Créer une étape...</a>
                    <div id="createStage" class="adminActionDiv" hidden>
                    <form action="php/utils/manageStages.php" method="post" enctype="multipart/form-data" target="newStage">
                        <input type="text" name="action" id="action" value="createNewStage" hidden/>
                        Numéro de l'étape : <input type="text" name="number" id="number"/><br/>
                        Annonce de course (pdf) : <input type="file" name="fichierAnnonce" id="fichierAnnonce"/><br/>
                        Extrait de carte (image) : <input type="file" name="fichierMap" id="fichierMap"/><br/>
                        <input type="submit" value="Envoyer" id="uploadButton"/>
                        
                    </form>
                    <iframe id="newStage" hidden></iframe>
                    </div>
                    <script type="text/javascript">$(document).ready(function(){
                            $("#createStageToggle").click(function(){
                            $("#createStage").toggle();})})
                    </script>

                </li>

                <li><a href="#" id="stageListToggle">Liste des étapes...</a>
                    <div id="stageList" class="adminActionDiv" hidden>

END;
        outputStagesList();
        echo<<<END
                    </div>
                    <script type="text/javascript">$(document).ready(function(){
                            //toggle
                            $("#stageListToggle").click(function(){
                            $("#stageList").toggle();})})

                    </script>

                </li>

            </ul>
        </div>
    </p>



END;

    /*
     * News management
     *
     */


     //TODO : html tag support in news!!


    //add news in newsfeed (table of all news), delete old : news management
    echo <<<END
<!--
<br/><br/>
    <h2>Gestion des news</h2>
    <p>
    <a href="#" class=adminAreaToggle id="newsManagement">Afficher...</a>

    <div id="newsManagementArea" hidden>

    <form id="addnews">
        <input type='text'/>
        <input type='submit' value='Soumettre'/>
    </form>

    </div>
    </p>
-->

END;


    



    //launch global results calculation

    //download global results (as csv only?)
    echo <<<END
    <h2>Calcul du classement</h2>
     <p>
    <a href="#" class=adminAreaToggle id="classementManagement">Afficher...</a>

    <div id="classementManagementArea" hidden>

        <button id="calculateResults">Calculate Results...</button><br/>
        <p><iframe id="resultsCalculationWindow" height="200" width="50%" hidden></iframe></p>
        <script>$(document).ready(function(){ $("#calculateResults").click(function(){ $("#resultsCalculationWindow").attr("src","php/utils/classement.php"); $("#resultsCalculationWindow").show();})})</script>


    </div>
    </p>
    


END;

    



    /**
     * Locals specific scripts
     * (link handling, etc)
     *
     */

    echo <<<END
    <script>
    $(document).ready(function(){

        

        //toggle areas
    
        $(".adminAreaToggle").click(function(){
            if($(this).text()=="Masquer...") $(this).text("Afficher...");
            else $(this).text("Masquer...");
            $("#"+$(this).attr("id")+"Area").toggle();
        });


    })
    </script>

END;

}


?>
