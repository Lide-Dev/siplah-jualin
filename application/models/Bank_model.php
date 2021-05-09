<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bank_model extends CI_Model
{
    /**
     * Get list or specified bank supported on this system.
     *
     * @param [type] $id
     * @return void
     */
    public function get_bank($id = null)
    {
        if (empty($id)) {
            return $query = $this->db->get('bank_codes')->result();
        } else {
            $id = clean_number($id);
            $this->db->where('id', $id);
            $query = $this->db->get('bank_codes');
            return $query->first_row();
        }
    }

    public function valid_bank($value)
    {
        $value = clean_number($value);
        $this->db->where("id", $value);
        $count = $this->db->get("bank_codes")->num_rows();
        $this->form_validation->set_message('valid_bank', 'Bank pada input {field} tidak valid.');

        return $count > 0;
    }
}
