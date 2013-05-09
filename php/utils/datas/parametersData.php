<?php

function getParameter($paramName){
    //load xml parameters
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/parameters.xml");
    $param = $all->getElementsByTagName($paramName)->item(0);
    return $param->nodeValue;
    
}



function setParameter($paramName,$paramValue){

    //in xml file (add or delete some news)
    //load doc to edit
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/parameters.xml");

    //change statut
    $param = $all->getElementsByTagName($paramName)->item(0);
    $param->nodeValue = $paramValue ;

    return (bool) $all->save("../../data/parameters.xml");

}


?>
