<?php



 /*
 Encripta el contenido de la variable, enviada como parametro.
  */
 function encrypt($valor) {
    $clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
    $method = 'aes-256-cbc';
    $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
     return openssl_encrypt ($valor, $method, $clave, false, $iv);
 };
 /*
 Desencripta el texto recibido
 */
function decrypt($valor){
    $clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';
    $method = 'aes-256-cbc';
    $iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
    $encrypted_data = base64_decode($valor);
    return openssl_decrypt($valor, $method, $clave, false, $iv);
};

function getSecret(){
    return 'YYF1kwvIw41hGY99lS5asUbY0JVHLXzd';
}