<?php 
/*
    $clave = "123456";
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    echo $hash;
    var_dump(password_verify($clave, $hash));
    */
    $date = new DateTime();
    $daynum = date("w", strtotime($date->format('D')));
    $daynum = $daynum == 0 ? 7:$daynum;
?>