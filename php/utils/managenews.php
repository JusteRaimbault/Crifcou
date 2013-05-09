<?php
//idem security check is necessary
//same check as for entries
if(($_POST['verified']!='ok')){
    include '../../html/onedoesnotsimply.html';
}
else{
    //load news DOM
    $newsDOM = new DOMDocument("1.0", "UTF-8");
    $newsDOM->load("../../data/news.xml");


    //check action to do in xml file (add or delete some news)
    if($_POST['action']=="add"){
        //add the last news (id is the last +1)

        //load parameters DOM
        $parametersDOM = new DOMDocument("1.0", "UTF-8");
        $parametersDOM->load("../../data/parameters.xml");

        //get the last id
        //$entries = $parametersDOM->getElementsByTagName("lastID")[0]


        
        
    }
    else if ($_POST['action']=='delete'){
        //delete the new with the given id


        
    }

    //if action is not set, nothing to do here! (a priori not possible, or if root access directly the page)
}

?>
