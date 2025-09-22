<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property User_model $User_model
 */
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // 2 case: get one and get all user
    public function index($id = null)
    {
        if ($this->input->method() === 'get') {
            if ($id === null) {

                $users = $this->User_model->read_users();
                return response($users, "Get users successfully");
            } else {
                $user = $this->User_model->read_user($id);
                if ($user) {

                    return response($user, "Get user successfully");
                } else {
                    http_response_code(400);
                    return response(null, "User not found");
                }
            }
        }
    }
}
