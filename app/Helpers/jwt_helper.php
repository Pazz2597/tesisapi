<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getUserFromRequest($request){
    $key = getenv('JWT_SECRET');
    $header = $request->getServer('HTTP_AUTHORIZATION');
    $token = null;

    // extract the token from the header
    if(!empty($header)) {
        if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            $token = $matches[1];
        }
    }

    if($token == null) return null;
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    return $decoded;
}
function getClaim($request, $claimName)
{
    $userData = getUserFromRequest($request);
    if ($userData == null) return null;
    $json = json_encode($userData);
    $data = json_decode($json, true);
    if(!isset($data[$claimName])) return null;
    $claim = $data[$claimName];
    return $claim;
}
