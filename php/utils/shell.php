<?php
session_start();
if(!isset($_SESSION['root'])){
    include '../../html/onedoesnotsimply.html';
}
else{
    $res = array();
    exec('ls /usr/bin', $res);
    foreach($res as $out){echo $out."<br/>";}
    
}
?>
