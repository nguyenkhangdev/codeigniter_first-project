<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('jwt_authenticate')) {
    function jwt_authenticate()
    {
        $ci = &get_instance();
        $ci->load->helper('jwt');


        $token = $ci->input->cookie('jwt_token', TRUE);
        if (!$token) {
            response(null, 'Unauthorized', 'error', 401);
        }

        $user = validate_jwt($token);
        if (!$user) {
            response(null, 'Unauthorized', 'error', 401);
        }

        return $user;
    }
}
