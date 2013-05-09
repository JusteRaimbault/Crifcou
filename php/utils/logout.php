<?php
session_start();

//theorically root is set but never know, if badass tries to hack!
if(isset($_SESSION['root'])){unset($_SESSION['root']);}

?>
