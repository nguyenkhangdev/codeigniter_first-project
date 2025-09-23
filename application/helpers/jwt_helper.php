<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if (!function_exists('generate_jwt')) {
    function generate_jwt($data)
    {
        try {
            $ci = &get_instance();
            $key = 'jwt_key';
            $expire = 3600;
            $payload = array(
                "iat" => time(),
                "exp" => time() + $expire,
                "data" => $data
            );
            $jwt = JWT::encode($payload, $key, 'HS256');

            return $jwt;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('validate_jwt')) {
    function validate_jwt($jwt)
    {
        $ci = &get_instance();
        $key = 'jwt_key';

        try {
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            return  (array) $decoded->data;
        } catch (Exception $e) {
            return false;
        }
    }
}
