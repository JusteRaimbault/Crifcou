<?php

    session_start();

    //relatively dirty way of checking login and psswd but no ther way because no sql database, and datas can't be accessible (like publics xml files)
    //security issue if php source file is stolen (possible??)
    
    if($_POST['login']==""/*ENTER HERE ROOT LOGIN*/&&$_POST['password']==""/*ENTER HERE ROOT PASSWORD*/){
        $_SESSION['root'] = 1;
    }



    
?>
