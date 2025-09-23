<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property User_model $User_model
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->helper(array('api', 'jwt', 'cookie'));
    }

    public function login()
    {
        try {
            $data = json_decode($this->input->raw_input_stream, true);
            if (!isset($data['email']) || !isset($data['password'])) {
                return response(null, 'Invalid input', 'error', 400);
            }

            $user = $this->User_model->get_user_by_email($data['email']);

            if (!$user || !password_verify($data['password'], $user['password_hash'])) {
                return response(null, 'Invalid credentials', 'error', 401);
            }
            $jwt = generate_jwt(['user_id' => $user['id'], 'email' => $user['email']]);
            $cookie = [
                'name' => 'jwt_token',
                'value' => $jwt,
                'expire' => 3600,
                'samesite' => 'Lax',
            ];
            set_cookie($cookie);
            return response($user, 'Login successfully');
        } catch (Exception $e) {
            return response(null, $e->getMessage(), "error", 500);
        }
    }
    public function logout()
    {
        try {
            delete_cookie('jwt_token', '', '/');
            return response(null, 'Logout successfully');
        } catch (Exception $e) {
            return response(null, $e->getMessage(), "error", 500);
        }
    }
}
