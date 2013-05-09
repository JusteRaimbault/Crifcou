<?php

/*
 * All in one file, more simple to handle, and classes are quite simple
 *
 */

class Runner {

    //Table where all existing runners are stored
    public static $runners = array();

    public static function construct($n,$s,$c,$sx){
        //need to check all existing runners if duplicate or similar name (error)
        //criteria : levensthein of name, surname < 2-3 ?
        $n = str_replace("\"","",$n);
        $s = str_replace("\"","",$s);
        $c = str_replace("\"","",$c);

        foreach(self::$runners as $runner){
            //echo "comparing ".$n." with ".$runner->name." : ".levenshtein($runner->name, ucfirst(strtolower($n)))."<br/>";

            if(levenshtein($runner->name, ucfirst(strtolower($n)))<=2&&levenshtein($runner->surname, strtoupper($s))<=2){
                //concat the club if different
                if(levenshtein(implode($runner->clubs), $c)>4)array_push($runner->clubs,$c);
                //a priori sex won't change !
                return $runner;
            }
        }

        //else need to create the new runner
        $res = new Runner($n, $s, $c, $sx) ;
        array_push(self::$runners, $res);
        return $res;
    }


    /*
     * Dynamic structure
     */
    //name
    public $name;
    //surname
    public $surname;
    //array of clubs
    public $clubs;//may be an array in case of differents entries, concat at export
    //sex as a string (H/D)
    public $sex;
    //array of pointers to the results
    public $results;
    //total points
    public $total;

    public function  __construct($n,$s,$c,$sx) {
        $this->name = ucfirst(strtolower(str_replace("\"","",$n))); $this->surname=strtoupper(str_replace("\"","",$s));$this->clubs=array();array_push($this->clubs,str_replace("\"","",$c));$this->sex = $sx;
        $this->results = array();
        $this->total=floatval(0.0);
    }

    public function  __toString() {
        $p="";foreach($this->results as $r){$p=$p.$r;}
        return "<p>Runner:<br/>Name:".$this->name.", Surname:".$this->surname.", Clubs:".$this->clubs.", Sex:".$this->sex."Points:".$this->total."<br/>";//", Results : ".$p."</p><br/>";
    }
}




class Result {

    public $runner;
    public $time;
    public $pm;
    public $circuit;
    public $points;
    public $stage;

    public function  __construct($r,$t,$p,$c,$s) {
        $this->runner = $r;
        //conversion in seconds
        $this->time = 0;
        $tt = explode(":",$t);
        if($tt[count($tt)-1]=="00"&&count($tt)==3&&strlen($tt[0])==2) {echo "exception:".$t.strlen($tt[0])."<br/>";array_pop($tt);}
        for($i=0;$i<$n=count($tt);$i++){$this->time = ($this->time * 60) + ((int) $tt[$i]);}
        $this->circuit = $c ;
        if($c!="A"||$c!="B"||$c!="C")$this->points = 0;
        $this->stage = $s;
        if ($p=="0"){$this->pm = FALSE;}else{$this->pm=TRUE;$this->points = 0;}
    }

    public function  __toString() {
        return "<p>Result:<br/>Runner:".$this->runner->name.", time:".$this->time.", circuit:".$this->circuit.", place:".$this->place.", points:".$this->points.",pm:".$this->pm.", stage:".$this->stage."</p><br/>";
    }

}


/**
 * Takes a csv file and calculates the resulting points.
 *
 * Adds new results to runners in the process.
 *
 *
 * @param string $results name of the csv result file
 */
function extract_points($results_file,$stageNumber){
    echo "<p>Extracting results from file $results_file<br/>";
    
    $results = file($results_file);

    $times = array();
    $times["A"]["H"]=array();
    $times["B"]["H"]=array();
    $times["C"]["H"]=array();
    $times["A"]["D"]=array();
    $times["B"]["D"]=array();
    $times["C"]["D"]=array();

    //simple results
    if(count(explode(";",$results[0]))==29){
        //begins at first line
        for($i=1;$i<count($results);$i++){
            $t = explode(";",$results[$i]);
            //construct runner
            $runner = Runner::construct($t[2], $t[1], $t[3], substr(str_replace("\"","",$t[4]), 0, 1));
            //contruct result
            $r = new Result($runner,str_replace("\"","",$t[5]),str_replace("\"","",$t[6]),substr(str_replace("\"","",$t[4]), 1, 1),$stageNumber);
            //associate time to res in time table
            $times[$r->circuit][$r->runner->sex][$r->time]=$r;
            //associate result with runner
            array_push($runner->results,$r);
            
            /**
             * 
             * ISSUE : if runner with pm has better time than best time?.. supress pms in best time calculation
             * 
             */
        }
    }
    else{//long results -> in fact only longs !
    //begins at first line
    //idem as up
        for($i=1;$i<count($results);$i++){
            $t = explode(";",$results[$i]);
            $runner = Runner::construct($t[4], $t[3], $t[14].$t[15], substr(str_replace("\"","",$t[18]), 0, 1));
            $r = new Result($runner,str_replace("\"","",$t[11]),str_replace("\"","",$t[12]),substr(str_replace("\"","",$t[18]), 1, 1),$stageNumber);
            $times[$r->circuit][$r->runner->sex][$r->time]=$r;
            array_push($runner->results,$r);
        }
    }


    /*
     * calculate points and place for each result
     */

    //sort to create results ; supress pms to solve issue of best time
    ksort($times["A"]["H"]);foreach($times["A"]["H"] as $t => $r){if($r->pm)unset($times["A"]["H"][$t]);}
    ksort($times["B"]["H"]);foreach($times["B"]["H"] as $t => $r){if($r->pm)unset($times["B"]["H"][$t]);}
    ksort($times["C"]["H"]);foreach($times["C"]["H"] as $t => $r){if($r->pm)unset($times["C"]["H"][$t]);}
    ksort($times["A"]["D"]);foreach($times["A"]["D"] as $t => $r){if($r->pm)unset($times["A"]["D"][$t]);}
    ksort($times["B"]["D"]);foreach($times["B"]["D"] as $t => $r){if($r->pm)unset($times["B"]["D"][$t]);}
    ksort($times["C"]["D"]);foreach($times["C"]["D"] as $t => $r){if($r->pm)unset($times["C"]["D"][$t]);}

    //get bests times
    $bests = array();
    echo "Best times:<br/>";
    $bests["A"]["H"] = array_shift(array_keys($times["A"]["H"]));echo "HA: ".$bests["A"]["H"]."<br/>";
    $bests["B"]["H"] = array_shift(array_keys($times["B"]["H"]));echo "HB: ".$bests["B"]["H"]."<br/>";
    $bests["C"]["H"] = array_shift(array_keys($times["C"]["H"]));echo "HC: ".$bests["C"]["H"]."<br/>";
    $bests["A"]["D"] = array_shift(array_keys($times["A"]["D"]));echo "DA: ".$bests["A"]["D"]."<br/>";
    $bests["B"]["D"] = array_shift(array_keys($times["B"]["D"]));echo "DB: ".$bests["B"]["D"]."<br/>";
    $bests["C"]["D"] = array_shift(array_keys($times["C"]["D"]));echo "DC: ".$bests["C"]["D"]."<br/>";

    //calculates points for each circuit, put the points and place in res tab
    foreach($times["A"]["H"] as $key=>$value){
        $diff = $key - $bests["A"]["H"] ;
        if($diff/60.0>15) $points = 215.0-(($diff/60.0) - 15.0 );
        else if($diff/60.0>5) $points =  235.0-(($diff/60.0 - 5.0)*2.0);
	else $points = 250.0 - (($diff/60)*3.0) ;
        if($points<0)$points=0;
        $times["A"]["H"][$key]->points = $points;
    }
    foreach($times["B"]["H"] as $key=>$value){
        $diff = $key - $bests["B"]["H"] ;
        $points = 150.0 - ($diff/60.0) ;
        if($points<0)$points=0;
        $times["B"]["H"][$key]->points = $points;
    }
    foreach($times["C"]["H"] as $key=>$value){
        $diff = $key - $bests["C"]["H"] ;
        $points = 100.0 - ($diff/60.0) ;
        if($points<0)$points=0;
        $times["C"]["H"][$key]->points = $points;
    }
    foreach($times["A"]["D"] as $key=>$value){
        $diff = $key - $bests["A"]["D"] ;
        if($diff/60.0>15) $points = 215.0-(($diff/60.0) - 15.0 );
        else if($diff/60.0>5) $points =  235.0-(($diff/60.0 - 5.0)*2.0);
	else $points = 250.0 - (($diff/60)*3.0) ;
        if($points<0)$points=0;
        $times["A"]["D"][$key]->points = $points;
    }
    foreach($times["B"]["D"] as $key=>$value){
        $diff = $key - $bests["B"]["D"] ;
        $points = 150.0 - ($diff/60.0) ;
        if($points<0)$points=0;
        $times["B"]["D"][$key]->points = $points;
    }
    foreach($times["C"]["D"] as $key=>$value){
        $diff = $key - $bests["C"]["D"] ;
        $points = 100.0 - ($diff/60.0) ;
        if($points<0)$points=0;
        $times["C"]["D"][$key]->points = $points;
    }

    echo "Processing completed !</p>";

}




/*
 * Calculation in itself
 *
 */

session_start();
//idem security check is necessary
if(!isset($_SESSION['root'])){
    include '../../html/onedoesnotsimply.html';
}
else{
   require 'datas/parametersData.php';
    
    //get stages from xml and proceed existing files
    $all = new DOMDocument("1.0", "UTF-8");
    $all->load("../../data/stages.xml");
    $xpath = new DOMXPath ($all);
    $stages = $all->getElementsByTagName("stage");
    foreach($stages as $stage){
        $n=$stage->getAttribute("number");
        $resultsCSV = (boolean) $xpath->query("resultsCSVAvailable",$stage)->item(0)->nodeValue;
        if($resultsCSV){extract_points("../../docs/resultsCSV/resultsCSV_$n.csv",$n);}
    }

    //foreach runner, calculate total points by taking 4 bests results
    foreach(Runner::$runners as $runner){
        //echo $runner->name."<br/>";
        $points = array();$del=array();$aux=array();
        foreach($runner->results as $r){foreach($runner->results as $rr){if ($rr!=$r && $rr->stage == $r->stage && $rr->points < $r->points){echo "two circuits:".$r.$rr;array_push($del,$rr);}};}
        foreach($del as $d) {unset($d);}
        foreach($runner->results as $r){$points[$r->points]=$r;$aux[strval(floor($r->points)).";".$r->stage]=$r->points;}
        krsort($aux);
        //foreach($points as $k=>$r){echo "points:$k<br/>";}
        $keys = array_keys($aux);
        for($i=0;$i<4;$i++){$p = floatval(substr($keys[$i],0,  strpos($keys[$i], ";")));/*echo "inTotal:$p<br/>";*/if($p!=NULL){$runner->total=$runner->total+floatval($points[$p]->points);}}
    }

    //creating final result table sorting by total points
    $final=array();$final["H"]=array();$final["D"]=array();
    foreach(Runner::$runners as $runner){
                //if($runner->surname=="BRAVO LERAMBERT"){echo $runner->name.$runner->total."<br/>";}
                //echo $runner->total.$runner->name.$runner->surname."<br/>";

        //WARNING only prov fix : if exactly same decimals (rational fraction), one key cancelled. wont happen in practice?
        
        if($runner->sex == "H"){$final["H"][1000 * $runner->total] = $runner ;}else{$final["D"][1000 * $runner->total] = $runner ;}
    }
    
    //sorting final arrays to get classement
    foreach($final["H"] as $key => $runner){echo $key." - ".$runner->name."<br/>";}
    
    krsort($final["H"]);
    krsort($final["D"]);

    
    
    
    //output results in csv files
    
    $season = getParameter("currentSeason");
    foreach($final as $s => $tab){
        $file = fopen("../../docs/globalResults/finalResults".$s."_".$season.".csv","w");
        if($file){
            //first line, important
            fwrite($file, "Place;Nom;Prenom;Club;");
            $m=0;
            foreach($stages as $stage){
                $n=$stage->getAttribute("number");fwrite($file,"Etape$n;");$m=max(array($n,$m));
            }
            fwrite($file,"Total\n");
            $i=1;
            foreach($tab as $runner){
                if($runner->surname=="BRAVO LERAMBERT"){echo $runner->name."<br/>";}

                fwrite($file,$i.";".$runner->surname.";".$runner->name.";".$runner->clubs[0].";");
                for($j=0;$j<$m;$j++){foreach($runner->results as $r){if($r->stage==($j+1)){fwrite($file, number_format($r->points, 2, ",",""));}};fwrite($file,";");}
                fwrite($file,number_format($runner->total,2,",","")."\n");
                $i++;
            } 
            fclose($file);
        }else{echo "Fail opening the file!<br/>";}
    }

}
?>
