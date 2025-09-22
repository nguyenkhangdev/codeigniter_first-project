<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function read_user($id)
    {
        return  $this->db->get_where('users', array('id' => $id))->row_array();
    }

    public function read_users()
    {
        return $this->db->get('users')->result_array();
    }

    public function create_user($data)
    {
        return  $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return  $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        return $this->db->delete('users',  array('id' => $id));
    }
}
