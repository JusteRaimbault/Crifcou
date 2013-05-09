<?php

echo<<<END
<form id="entriesForm" hidden>
        Nom : <input type="text" name="nom" required></input><br/>
        Prénom : <input type="text" name="prenom" required></input><br/>
        Université, Ecole (FFSU) ou club (FFCO) : <!--<select name="existingclub"><option value=""/>
END;
    $doc = new DOMDocument("1.0","UTF-8");
    $doc->load("../../data/entries.xml");
    $clubs = $doc->getElementsByTagName("Club");
    foreach($clubs as $club){
        $name = $club->getElementsByTagName("Name")->item(0)->nodeValue;
        echo "<option value=\"$name\">$name</option>";
    }
    echo <<<END
        </select>-->
        <input type="text" name="club" required></input><br/>
        Circuit :<br/>
        <input type="radio" name="circuit" value="A" required/>Circuit A <br/>
        <input type="radio" name="circuit" value="B" />Circuit B <br/>
        <input type="radio" name="circuit" value="C" />Circuit C <br/>

        Sexe : <input type="radio" name="sexe" value="H" required/>H  <input type="radio" name="sexe" value="D" />F<br/>

        <br/>
        Pour vérifier que vous n'êtes pas un robot, veuillez rentrer la valeur du captcha :
        <img src="php/utils/captcha.php"/>
        <br/>
        Valeur de la somme : <input type="text" name="captcha" required/><br/>
        <input type="submit" value="S'inscrire" id="submitEntryForm"></input>
    </form>

END;
?>
