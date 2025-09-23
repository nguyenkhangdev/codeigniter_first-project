<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task_model extends CI_Model
{
    public function read_task($id)
    {
        return  $this->db->get_where('tasks', array('id' => $id))->row_array();
    }

    public function read_tasks($user_id = null)
    {
        if ($user_id) {
            return  $this->db->get_where('tasks', array('user_id' => $user_id))->result_array();
        }
        return $this->db->get('tasks')->result_array();
    }

    public function create_task($data)
    {
        return  $this->db->insert('tasks', $data);
    }

    public function update_task($id, $data)
    {
        $this->db->where('id', $id);
        return  $this->db->update('tasks', $data);
    }

    public function delete_task($id)
    {
        return $this->db->delete('tasks',  array('id' => $id));
    }
}
