<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property Task_model $Task_model
 */
class Tasks extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Task_model');
        $this->load->helper('api', 'auth');
    }

    //CI3 not support nany method http so we must use check method in controller method index
    public function index($id = null)
    {
        try {
            $method = $this->input->method(TRUE); // GET | POST | PUT | DELETE
            $user = jwt_authenticate();

            switch ($method) {
                case 'GET':
                    if ($id === null) {
                        // GET /api/tasks
                        $tasks = $this->Task_model->read_tasks($user['id']);
                        return response($tasks, "Get tasks successfully");
                    } else {
                        // GET /api/tasks/:id
                        $task = $this->Task_model->read_task($id);
                        if ($task && $user['id'] === $task->user_id) {
                            return response($task, "Get task successfully");
                        } else {
                            return response(null, "Task not found", "error", 404);
                        }
                    }
                    break;

                case 'POST':
                    // POST /api/tasks
                    $data = json_decode($this->input->raw_input_stream, true);
                    if (!$data || !isset($data['title']) || !isset($data['description']) || !isset($data['status'])) {
                        return response(null, 'Invalid input', 'error', 400);
                    }
                    //Testing version
                    $data['user_id'] = $user['id'];

                    $createdTask = $this->Task_model->create_task($data);
                    if ($createdTask) {
                        return response($createdTask, "Create task successfully", "success", 201);
                    } else {
                        return response(null, "Create failed", "error", 400);
                    }
                    break;

                case 'PUT':
                    // PUT /api/tasks/:id
                    if ($id === null) {
                        return response(null, 'Missing task id', 'error', 400);
                    }
                    $data = json_decode($this->input->raw_input_stream, true);
                    if (!$data) {
                        return response(null, 'Invalid request', 'error', 400);
                    }

                    $task = $this->Task_model->read_task($id);
                    if (!$task || $user['id'] !== $task->user_id) {
                        return response(null, "Task not found", "error", 404);
                    }

                    $updatedTask = $this->Task_model->update_task($id, $data);
                    if ($updatedTask) {
                        return response($updatedTask, "Update task successfully");
                    } else {
                        return response(null, "Update failed", "error", 400);
                    }
                    break;

                case 'DELETE':
                    // DELETE /api/tasks/:id
                    if ($id === null) {
                        return response(null, 'Missing task id', 'error', 400);
                    }

                    $task = $this->Task_model->read_task($id);
                    if (!$task || $user['id'] !== $task->user_id) {
                        return response(null, "Task not found", "error", 404);
                    }

                    $deletedTask = $this->Task_model->delete_task($id);
                    if ($deletedTask) {
                        return response($deletedTask, "Delete task successfully");
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
