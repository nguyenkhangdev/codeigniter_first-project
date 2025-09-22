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
        header("Content-Type: application/json");
    }

    // 2 case: get one and get all user
    public function index($id = null)
    {
        if ($this->input->method() === 'get') {
            if ($id === null) {

                $users = $this->User_model->read_users();
                echo json_encode($users);
            } else {
                $user = $this->User_model->read_user($id);
                if ($user) {
                    echo json_encode($user);
                } else {
                    http_response_code(400);
                    echo json_encode(['message' => 'User not found']);
                }
            }
        }
    }
}
