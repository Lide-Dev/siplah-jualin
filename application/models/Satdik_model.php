<?php

use Ramsey\Uuid\Uuid;

defined('BASEPATH') or exit('No direct script access allowed');

class Satdik_model extends CI_Model
{
    public function get_satdik_by_id($id, $is_table_satdik_user = false)
    {
        $this->db->where($is_table_satdik_user ? "satdik_id" : "id", $id);
        $result = $this->db->get($is_table_satdik_user ? "satdik_user_profiles" : "satdik_profiles");
        return $result->row();
    }

    public function get_satdik_user_by_id($id)
    {
        $this->db->where("user_id", $id);
        $result = $this->db->get("satdik_user_profiles");
        return $result->row();
    }

    public function set_satdik($data)
    {
        $data["phone_number"] = substr($data["phone_number"], 1);
        $data["city_id"] = $this->location_model->get_city_by_region($data["region_id"])->id;
        unset($data["region_id"]);
        if (empty($this->get_satdik_by_id($data["id"]))) {
            $this->db->insert("satdik_profiles", $data);
        } else {
            $this->db->where("id", $data["id"]);
            $this->db->update("satdik_profiles", $data);
        }
    }

    public function set_satdik_user($data, $auth_data)
    {
        $satdik_user = $this->get_satdik_user_by_id($auth_data["id"]);
        if (empty($satdik_user)) {
            $auth_data["username"] = $this->generate_uniqe_username($data["name"]);
            $auth_data["slug"] = $this->generate_uniqe_slug($data["name"]);
            $auth_data["role"] = "member";
            $auth_data['banned'] = 0;
            $auth_data['created_at'] = date('Y-m-d H:i:s');
            $auth_data['token'] = generate_token();
            $auth_data['email_status'] = 1;
            $data["phone_number"] = substr($data["phone_number"], 1);
            $this->db->insert("users", $auth_data);
            $this->db->insert("satdik_user_profiles", $data);
        } else {
            $data["id"] = $satdik_user->id;
            $data["phone_number"] = substr($data["phone_number"], 1);
            $this->db->where("id", $data["id"]);
            $this->db->update("satdik_user_profiles", $data);
        }
    }
    public function generate_uniqe_username($username)
    {
        $new_username = $username;
        if (!empty($this->get_user_by_username($new_username))) {
            $new_username = $username . " 1";
            if (!empty($this->get_user_by_username($new_username))) {
                $new_username = $username . " 2";
                if (!empty($this->get_user_by_username($new_username))) {
                    $new_username = $username . " 3";
                    if (!empty($this->get_user_by_username($new_username))) {
                        $new_username = $username . "-" . uniqid();
                    }
                }
            }
        }
        return $new_username;
    }

    public function generate_uniqe_slug($username)
    {
        $slug = str_slug($username);
        if (!empty($this->get_user_by_slug($slug))) {
            $slug = str_slug($username . "-1");
            if (!empty($this->get_user_by_slug($slug))) {
                $slug = str_slug($username . "-2");
                if (!empty($this->get_user_by_slug($slug))) {
                    $slug = str_slug($username . "-3");
                    if (!empty($this->get_user_by_slug($slug))) {
                        $slug = str_slug($username . "-" . uniqid());
                    }
                }
            }
        }
        return $slug;
    }

    public function get_user_by_username($username)
    {
        $username = remove_special_characters($username);
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }
    public function get_user_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('users');
        return $query->row();
    }

    /**
     * Validate phone number
     *
     * @param mixed $value
     * @param boolean $return_first_digit
     * If true, return the first digit specified that was matches with value like `62`, `08`, or `8`. Still return `false` if phone number not valid.
     * @return boolean|string
     */
    public function valid_phone_number($value, $return_first_digit = false)
    {
        if (!ctype_digit($value)) {
            return false;
        }

        if (substr($value, 0, 2) == "62") {
            $limitup = 11;
            $first_digit = "62";
        } else if (substr($value, 0, 2) == "08") {
            $limitup = 10;
            $first_digit = "08";
        } else if (substr($value, 0, 1) == "8") {
            $limitup = 9;
            $first_digit = "8";
        } else {
            return false;
        }
        if ($return_first_digit) {
            return $first_digit;
        } else
            return strlen($value) >= $limitup && strlen($value) < 15;
    }
}

// class Satdik{

// }