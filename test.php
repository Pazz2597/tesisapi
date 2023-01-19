<?php 
    $clave = "123456";
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    echo $hash;
    var_dump(password_verify($clave, $hash));
?>