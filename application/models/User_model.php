<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function read_user($id)
    {
        $user = $this->db->get_where('users', array('id' => $id))->row_array();
        if ($user) {
            unset($user['password_hash']);
        }
        return $user;
    }

    public function read_users($user_id = null)
    {
        if ($user_id) {
            return  $this->db->get_where('users', array('user_id' => $user_id))->row_array();
        }
        return $this->db->get('users')->result_array();
    }

    public function create_user($data)
    {
        if ($this->db->insert('users', $data)) {
            echo "asaa";
            $id = $this->db->insert_id(); // ID vừa tạo
            $new_user = $this->db->get_where('users', ['id' => $id])->row_array();
            if ($new_user) {
                unset($new_user['password_hash']);
            }
            return $new_user;
        }
        return false;
    }

    // public function update_user($id, $data)
    // {
    //     $this->db->where('id', $id);
    //     return  $this->db->update('users', $data);
    // }

    // public function delete_user($id)
    // {
    //     return $this->db->delete('users',  array('id' => $id));
    // }

    public function get_user_by_email($email)
    {
        return  $this->db->get_where('users', array('email' => $email))->row_array();
    }
}
