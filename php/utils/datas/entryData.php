<?php

function newEntry($nom,$prenom,$sexe,$circuit,$club){
    
    //deal with possible \n in club
    $club = str_replace("\n", "", $club);

     /**
     *
     * Add entry to current XML IOFdtd document.
     *
     */

    $entries = new DOMDocument("1.0", "UTF-8");
    $entries->load("../../data/entries.xml");

    //club of the runner
    //-> check in doc if already exists. If not, create and insert.
    $exists = FALSE;
    $clubentries = $entries->getElementsByTagName("ClubEntry");
    foreach ($clubentries as $clubentry) {
        $clubname = $clubentry->getElementsByTagName("Club")->item(0)->getElementsByTagName("Name")->item(0)->nodeValue;
        if($clubname==$club){
            //club already exists, add the runner to the ClubEntry
            $entry = $entries->createElement("Entry");

            //append person to Entry
            $personname = $entries->createElement("PersonName");
            $personname->appendChild(new DOMElement("Family",$nom));
            $personname->appendChild(new DOMElement("Given",$prenom));
            $entry->appendChild(new DOMElement("Person"))->appendChild($personname);

            //append class
            $entry->appendChild(new DOMElement("EntryClass"))->appendChild(new DOMElement("ClassShortName",$sexe.$circuit));

            $clubentry->appendChild($entry);

            $exists = TRUE;
        }
    }

    //if the club didn't exist, create it
    if(!$exists){

        $clubentry = $entries->createElement("ClubEntry");

        $clubentry->appendChild(new DOMElement("Club"))->appendChild(new DOMElement("Name",$club));

        $entry = $entries->createElement("Entry");

        //append person to Entry
        $personname = $entries->createElement("PersonName");
        $personname->appendChild(new DOMElement("Family",$nom));
        $personname->appendChild(new DOMElement("Given",$prenom));
        $entry->appendChild(new DOMElement("Person"))->appendChild($personname);

        //append class
        $entry->appendChild(new DOMElement("EntryClass"))->appendChild(new DOMElement("ClassShortName",$sexe.$circuit));
        $clubentry->appendChild($entry);

        $entries->getElementsByTagName("EntryList")->item(0)->appendChild($clubentry);
    }



    //normalize document then save it
    $entries->normalizeDocument();
    $entries->save("../../data/entries.xml");


    /**
     *
     * Add entry to current csv document.
     *
     * Simple FileWrite in text File
     *
     */
     $csv = fopen("../../data/entries.csv", "a");
     fwrite($csv,";;;$nom;$prenom;;$sexe;;0;;;;0;1;$club;;;1;".$sexe.$circuit.";;;;;;;;;;;;;;;;;0;0,00;0;\n");
     fclose($csv);
     
     /**
      * Write pass (hashcode) in securised commented php file
      * Entry is supposed unique for (name,surname,circuit)
      * 
      * TODO --> ADD CHECK BEFORE, RETURN REPONSE IF NEEDED
      * 
      */
     $php = fopen("../../data/entries.php","a");
     $hash = ""; for($i=0;$i<5;$i++){$hash = $hash.chr(rand(60,120));}
     fwrite($php,"<?php/*;$nom$prenom$circuit;$hash;*/?>\n");
     fclose($php);

     /**
      * Send confirmation mail
      * 
      * TODO still need test, then ask for runner mail
      */
     //mail("raimbaultjwin@hotmail.com","CRIFCOU Inscriptions","$nom$prenom$circuit;$hash entered with success!");
     
}


/**
 * 
 * 
 * @param type $nom
 * @param type $prenom
 * @param type $circuit
 */
function deleteEntry($nom,$prenom,$circuit){
    //delete in XML
    
    
    
    //delete in csv
    
    
    
    //delete in php
    
}



function deleteEntries(){
    //delete in xml
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/entries.xml");
    $entries = $all->getElementsByTagName("EntryList")->item(0);
    $clubentries = $entries->getElementsByTagName("ClubEntry");
    foreach($clubentries as $clubentry){
        $entries->removeChild($clubentry);
    }
    $all->save("../../data/entries.xml");

    //delete in csv
    unlink("../../data/entries.csv");
    $csv = fopen("../../data/entries.csv", "a");
    fwrite($csv,"N° dép.;Puce;Ident. base de données;Nom;Prénom;Né;S;Plage;nc;Départ;Arrivée;Temps;Evaluation;N° club;Nom;Ville;Nat;N° cat.;Court;Long;Num1;Num2;Num3;Text1;Text2;Text3;Adr. nom;Rue;Ligne2;Code Post.;Ville;Tél.;Fax;E-mail;Id/Club;Louée;Engagement;Payé;;;\n");
    fclose($csv);
    
    //delete php
    unlink("../../data/entries.php");
    $php = fopen("../../data/entries.php", "a");
    fclose($php);


}



?>
