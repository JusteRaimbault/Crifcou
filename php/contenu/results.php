<p>Classement général du CRIFCOU 2013.</p>
<div id="resultsGlobals">
<?php
$men = file("../../docs/globalResults/finalResultsH_2013.csv");
$fst = explode(";",array_shift($men));

echo "<h1><a href=\"#\" id=\"displayGlobalResultsMen\">Hommes...</a></h1><div id=\"globalResultsMen\" hidden><table id=\"resultsTable\" class=\"table table-bordered table-striped table-condensed\"><thead><tr>";
foreach($fst as $f){echo "<th>".$f."</th>";}
echo "</tr></thead><tbody>";

foreach($men as $line){
    echo "<tr>";
    $res = explode(";",$line);
    foreach($res as $r){echo "<th>".$r."</th>";}
    echo "</tr>";
}


echo "</tbody></table></div>";

$women = file("../../docs/globalResults/finalResultsD_2013.csv");
$fstw = explode(";",array_shift($women));

echo "<h1><a href=\"#\" id=\"displayGlobalResultsWomen\">Dames...</a></h1><div id=\"globalResultsWomen\" hidden><table id=\"resultsTable\" class=\"table table-bordered table-striped table-condensed\"><thead><tr>";
foreach($fstw as $f){echo "<th>".$f."</th>";}
echo "</tr></thead><tbody>";

foreach($women as $line){
    echo "<tr>";
    $res = explode(";",$line);
    foreach($res as $r){echo "<th>".$r."</th>";}
    echo "</tr>";
}


echo "</tbody></table></div>";
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#displayGlobalResultsMen").click(function(){$("#globalResultsWomen").hide();$("#globalResultsMen").toggle(500,function(){});})
        $("#displayGlobalResultsWomen").click(function(){$("#globalResultsMen").hide();$("#globalResultsWomen").toggle(500,function(){});})
    })
</script>