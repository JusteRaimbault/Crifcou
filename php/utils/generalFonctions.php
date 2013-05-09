<?php


/*
 *
 * //unuseful but keep to memorize data structure
    function fromMapPdoObjectToHtml($object,$tableid){
        echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>Nom de la carte</th><th>Fichier vectoriel</th><th>Lieu</th><th>Coordonnées</th><th>Propriétaire</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $map) {
            $vecname = substr($map->filename, 0, strlen($map->filename) - strlen(end(explode(".",$map->filename)))-1).".ocd";
            if($map->processed=="1") $li = "<a href=\"cartes/$vecname\">$vecname</a>";
            else $li = "Non disponible";
            echo "<tr><td><p>$map->filename            <a href=\"cartes/$map->filename\" class=\"lightbox\" id=\"$map->filename\" title=\"$map->filename\">Voir: <img src=\"cartes/$map->filename\" height=\"20\" width=\"20\"/></a></p></td><td>$li</td><td>".$map->place."</td><td>lat:".$map->lat." lon:".$map->lon."</td><td>".$map->owner."</td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
        echo '<script type="text/javascript">$(document).ready(function(){$("a.lightbox").lightBox();})</script>';
    }

    function fromMapPdoObjectToHtmlRoot($object,$tableid){
        echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>Nom de la carte</th><th>Lieu</th><th>Coordonnées</th><th>Propriétaire</th><th>Procéder</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $map) {
            echo "<tr><td>$map->filename</td><td>".$map->place."</td><td>lat:".$map->lat." lon:".$map->lon."</td><td>".$map->owner."</td><td id=\"caseconvert$map->filename\"><a href=\"#\" class=\"process\" id=\"$map->filename\">Lancer la conversion</a></td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
    }

    function fromUserPdoObjectToHtml($object,$tableid){
        echo "<table id=\"".$tableid."\" class=\"dtable\">";
        echo "<thead><tr><th>login</th><th>Nom</th><th>Mail</th><th>Validation</th></tr></thead>".PHP_EOL."<tbody>";
        foreach ($object as $user) {
            echo "<tr><td>$user->login</td><td>$user->nom</td><td>$user->mail</td><td id=\"case$user->login\"><a href=\"#\" class=\"valider\" id=\"$user->login\">Valider l'inscription</a></td></tr>".PHP_EOL;
        }
        echo "</tbody></table>";
    }
 *
 *
 */




/**
 *
 * Echoes the html code for a single js function call at this moment of the document.
 *
 * @param <String> $functionName
 */
function jscall($functionName){
     echo "<script type=\"text/javascript\">$(document).ready(function(){".$functionName."();})</script>";
}

    

/*
 * Function to load news on acceuil page.
 *
 * Beware, html is supported, so the root are not supposed to inject badass code. (not open to public so no pb)
 *
 */
function loadNews(){

    $doc = simplexml_load_file('../../data/news.xml');//beware of location, function is called from utils (pageload.php)

    $news = $doc->new;

    foreach($news as $new) {

       echo "<div class=\"news\">";

       echo "<h2>".$new->title."</h2>";//have a title seems legit
       if($new->author!="") echo "<h3>Par ".$new->author."</h3>";
       if($new->date!="") echo "<h3>le ".$new->date."</h3>";
       echo "<p>".$new->resume."</p>".PHP_EOL;//idem for resume

       if(strlen($new->text)>0){
       echo "<p><div class=\"newstext\" id=\"text".$new->canontitle."\" hidden>".$new->text."</div></p>";
       echo "<p><button class=\"newstoggle btn\" id=\"".$new->canontitle."\">Afficher le texte...</button></p>";
       }
       echo "</div>";
       
    }




    //call toggle function


    jscall("toggleNews");

}



    //function to load news as datatable for the admin page
    //(globally the same as preced, but not same html structure)
    function loadNewsAsDatatableObject(){

        //TODO


    }



    function loadEntriesList(){
        $entries = file("../../data/entries.csv");
        $numEntries = count($entries)-1;
        echo "<p>Il y a actuellement $numEntries inscrits.</p>";
        //Load from csv? -> will keep the order
        echo "<table id=\"entriestable\" class=\"dtable\">";
        echo "<thead><tr><th>Nom</th><th>Prénom</th><th>Fac/Club</th><th>Circuit</th></tr></thead>".PHP_EOL."<tbody>";
        foreach($entries as $entry){
            if($entries[0]!=$entry){
                $values = explode(";", $entry);
                echo "<tr><td>$values[3]</td><td>$values[4]</td><td>$values[14]</td><td>$values[18]</td></tr>".PHP_EOL;
            }
        }
        echo "</tbody></table>";

        jscall("manageEntryList");

    }



?>
