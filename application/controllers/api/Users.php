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
        $this->load->helper('api');
    }

    //CI3 not support nany method http so we must use check method in controller method index
    public function index($id = null)
    {
        try {
            $method = $this->input->method(TRUE); // GET | POST | PUT | DELETE

            switch ($method) {
                case 'GET':
                    if ($id === null) {
                        // GET /api/users
                        $users = $this->User_model->read_users();
                        return response($users, "Get users successfully");
                    } else {
                        // GET /api/users/:id
                        $user = $this->User_model->read_user($id);
                        if ($user) {
                            return response($user, "Get user successfully");
                        } else {
                            return response(null, "User not found", "error", 404);
                        }
                    }
                    break;

                case 'POST':
                    // POST /api/users
                    $data = json_decode($this->input->raw_input_stream, true);
                    if (!$data  || !isset($data['email']) || !isset($data['password'])) {
                        return response(null, 'Invalid input', 'error', 400);
                    }
                    $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    unset($data['password']);
                    $createdUser = $this->User_model->create_user($data);
                    if ($createdUser) {
                        return response($createdUser, "Create user successfully", "success", 201);
                    } else {
                        return response(null, "Create failed", "error", 400);
                    }
                    break;

                case 'PUT':
                    // PUT /api/users/:id
                    if ($id === null) {
                        return response(null, 'Missing user id', 'error', 400);
                    }
                    $data = json_decode($this->input->raw_input_stream, true);
                    if (!$data) {
                        return response(null, 'Invalid request', 'error', 400);
                    }
                    if (isset($data['password'])) {
                        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
                        unset($data['password']);
                    }
                    $updatedUser = $this->User_model->update_user($id, $data);
                    if ($updatedUser) {
                        return response($updatedUser, "Update user successfully");
                    } else {
                        return response(null, "Update failed", "error", 400);
                    }
                    break;

                case 'DELETE':
                    // DELETE /api/users/:id
                    if ($id === null) {
                        return response(null, 'Missing user id', 'error', 400);
                    }
                    $deletedUser = $this->User_model->delete_user($id);
                    if ($deletedUser) {
                        return response($deletedUser, "Delete user successfully");
                    } else {
                        return response(null, "Delete failed", "error", 400);
                    }
                    break;

                default:
                    return response(null, "Method not allowed", "error", 405);
            }
        } catch (Exception $e) {
            return response(null, $e->getMessage(), "error", 500);
        }
    }
}
