<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Output $output
 */
if (!function_exists('response')) {
    function response($data = null, $message = '', $status = 'success', $http_code = 200)
    {
        $ci = &get_instance();
        $ci->output
            ->set_content_type('application/json')
            ->set_status_header($http_code)
            ->set_output(json_encode([
                'status'  => $status,
                'message' => $message,
                'data'    => $data
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }
}
