<?php

use Ramsey\Uuid\Uuid;

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    private $legal_status = ["individual" => 1, "pkp" => 2, "non_pkp" => 3];
    private $business_type = ["micro" => 1, "small" => 2, "medium" => 3, "non_umkm" => 4];

    //input values
    public function input_values()
    {
        $data = array(
            'username' => remove_special_characters($this->input->post('username', true)),
            'email' => $this->input->post('email', true),
            'first_name' => $this->input->post('first_name', true),
            'last_name' => $this->input->post('last_name', true),
            'password' => $this->input->post('password', true)
        );
        return $data;
    }

    //login
    public function login($role = null)
    {
        $this->load->library('bcrypt');

        $data = $this->input_values();
        $user = $this->get_user_by_email($data['email'], $role);

        if (!empty($user)) {
            //check password
            if ($role == "vendor" && $user->is_active_shop_request != 0) {
                $this->session->set_flashdata('error', trans("login_error"));
                return false;
            }
            if (!$this->bcrypt->check_password($data['password'], $user->password)) {
                $this->session->set_flashdata('error', trans("login_error"));
                return false;
            }
            if ($user->email_status != 1) {
                $this->session->set_flashdata('error', trans("msg_confirmed_required") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email('" . $user->id . "','" . $user->token . "');\">" . trans("resend_activation_email") . "</a>");
                return false;
            }
            if ($user->banned == 1) {
                $this->session->set_flashdata('error', trans("msg_ban_error"));
                return false;
            }
            //set user data
            $user_data = array(
                'modesy_sess_user_id' => $user->id,
                'modesy_sess_user_email' => $user->email,
                'modesy_sess_user_role' => $user->role,
                'modesy_sess_logged_in' => true,
                'modesy_sess_app_key' => $this->config->item('app_key'),
            );
            $this->session->set_userdata($user_data);
            return true;
        } else {
            $this->session->set_flashdata('error', trans("login_error"));
            return false;
        }
    }

    //login direct
    public function login_direct($user)
    {
        //set user data
        $user_data = array(
            'modesy_sess_user_id' => $user->id,
            'modesy_sess_user_email' => $user->email,
            'modesy_sess_user_role' => $user->role,
            'modesy_sess_logged_in' => true,
            'modesy_sess_app_key' => $this->config->item('app_key'),
        );

        $this->session->set_userdata($user_data);
    }

    //login with facebook
    public function login_with_facebook($fb_user)
    {
        if (!empty($fb_user)) {
            $user = $this->get_user_by_email($fb_user->email);
            //check if user registered
            if (empty($user)) {
                if (empty($fb_user->name)) {
                    $fb_user->name = "user-" . uniqid();
                }
                $username = $this->generate_uniqe_username($fb_user->name);
                $slug = $this->generate_uniqe_slug($username);
                //add user to database
                $data = array(
                    'facebook_id' => $fb_user->id,
                    'email' => $fb_user->email,
                    'email_status' => 1,
                    'token' => generate_token(),
                    'role' => "member",
                    'username' => $username,
                    'first_name' => $fb_user->name,
                    'slug' => $slug,
                    'avatar' => "https://graph.facebook.com/" . $fb_user->id . "/picture?type=large",
                    'user_type' => "facebook",
                    'created_at' => date('Y-m-d H:i:s')
                );
                if ($this->general_settings->vendor_verification_system != 1) {
                    $data['role'] = "vendor";
                }
                if (!empty($data['email'])) {
                    $this->db->insert('users', $data);
                    $user = $this->get_user_by_email($fb_user->email);
                    $this->login_direct($user);
                }
            } else {
                //login
                $this->login_direct($user);
            }
        }
    }

    //login with google
    public function login_with_google($g_user)
    {
        if (!empty($g_user)) {
            $user = $this->get_user_by_email($g_user->email);
            //check if user registered
            if (empty($user)) {
                if (empty($g_user->name)) {
                    $g_user->name = "user-" . uniqid();
                }
                $username = $this->generate_uniqe_username($g_user->name);
                $slug = $this->generate_uniqe_slug($username);
                //add user to database
                $data = array(
                    'google_id' => $g_user->id,
                    'email' => $g_user->email,
                    'email_status' => 1,
                    'token' => generate_unique_id(),
                    'role' => "member",
                    'username' => $username,
                    'first_name' => $g_user->name,
                    'slug' => $slug,
                    'avatar' => $g_user->avatar,
                    'user_type' => "google",
                    'created_at' => date('Y-m-d H:i:s')
                );
                if ($this->general_settings->vendor_verification_system != 1) {
                    $data['role'] = "vendor";
                }
                if (!empty($data['email'])) {
                    $this->db->insert('users', $data);
                    $user = $this->get_user_by_email($g_user->email);
                    $this->login_direct($user);
                }
            } else {
                //login
                $this->login_direct($user);
            }
        }
    }

    //login with vk
    public function login_with_vk($vk_user)
    {
        if (!empty($vk_user)) {
            $user = $this->get_user_by_email($vk_user->email);
            //check if user registered
            if (empty($user)) {
                if (empty($vk_user->name)) {
                    $vk_user->name = "user-" . uniqid();
                }
                $username = $this->generate_uniqe_username($vk_user->name);
                $slug = $this->generate_uniqe_slug($username);
                //add user to database
                $data = array(
                    'google_id' => $vk_user->id,
                    'email' => $vk_user->email,
                    'email_status' => 1,
                    'token' => generate_unique_id(),
                    'role' => "member",
                    'username' => $username,
                    'first_name' => $vk_user->name,
                    'slug' => $slug,
                    'avatar' => $vk_user->avatar,
                    'user_type' => "vkontakte",
                    'created_at' => date('Y-m-d H:i:s')
                );
                if ($this->general_settings->vendor_verification_system != 1) {
                    $data['role'] = "vendor";
                }
                if (!empty($data['email'])) {
                    $this->db->insert('users', $data);
                    $user = $this->get_user_by_email($vk_user->email);
                    $this->login_direct($user);
                }
            } else {
                //login
                $this->login_direct($user);
            }
        }
    }

    //generate uniqe username
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

    //generate uniqe slug
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

    //register
    public function register()
    {
        $this->load->library('bcrypt');

        $data = $this->auth_model->input_values();
        $data['username'] = remove_special_characters($data['username']);
        //secure password
        $data['password'] = $this->bcrypt->hash_password($data['password']);
        $data['role'] = "member";
        $data['user_type'] = "registered";
        $data["slug"] = $this->generate_uniqe_slug($data["username"]);
        $data['banned'] = 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['token'] = generate_token();
        $data['email_status'] = 1;
        if ($this->general_settings->email_verification == 1) {
            $data['email_status'] = 0;
        }
        if ($this->general_settings->vendor_verification_system != 1) {
            $data['role'] = "vendor";
        }
        if ($this->db->insert('users', $data)) {
            $last_id = $this->db->insert_id();
            if ($this->general_settings->email_verification == 1) {
                $user = $this->get_user($last_id);
                if (!empty($user)) {
                    $this->session->set_flashdata('success', trans("msg_register_success") . " " . trans("msg_send_confirmation_email") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email_register('" . $user->id . "','" . $user->token . "');\">" . trans("resend_activation_email") . "</a>");
                    $this->send_email_activation_ajax($user->id, $user->token);
                }
            }
            return $last_id;
        } else {
            return false;
        }
    }

    /**
     * Register user with role supplier
     *
     * @param array $data
     * @param array $file
     * @return void
     * @author Herlandro T. <herlandrotri@gmail.com>
     */
    public function register_supplier($data, $file)
    {
        $this->load->model("upload_model");
        //udah install composer install di proyek ka?
        $this->db->trans_begin();
        $this->load->library('bcrypt');
        $id = Uuid::uuid4()->toString();
        $this->db->insert('users', [
            "id" => $id,
            "email" => $data["user"]["email"],
            "password" => $this->bcrypt->hash_password($data["user"]['password']),
            "is_active_shop_request" => 1,
            "role" => "vendor",
            // "email_status" => 1,
            "username" => $this->generate_uniqe_username($data["profile"]["supplier_name"]),
            "slug" => $this->generate_uniqe_slug($data["profile"]["supplier_name"]),
            'token' => generate_token()
        ]);

        $user_id = $id;
        $file_info = $this->upload_model->upload_document_supplier($file, $user_id);
        // dd($file_info);
        $data["profile"]["user_id"] = $user_id;
        $data["profile"]["legal_status_id"] = $this->legal_status[$data["profile"]["legal_status_id"]];
        $data["profile"]["business_type_id"] = $this->business_type[$data["profile"]["business_type_id"]];
        foreach ($file_info as $key => $value) {
            $data["profile"]["{$key}_path"] = $value;
        }
        $this->db->insert("supplier_profiles", $data["profile"]);
        $this->db->trans_complete();
        if ($this->general_settings->email_verification == 1) {
            $user = $this->get_user($user_id);
            if (!empty($user)) {
                $this->session->set_tempdata('success', trans("msg_register_success") . " " . trans("msg_send_confirmation_email") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email_register('" . $user->id . "','" . $user->token . "');\">" . trans("resend_activation_email") . "</a>", 10);
                $this->send_email_activation_ajax($user->id, $user->token);
            }
        }
        return $this->db->trans_status();
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
    //send email activation
    public function send_email_activation($user_id, $token)
    {
        if (!empty($user_id)) {
            $user = $this->get_user($user_id);
            if (!empty($user)) {
                if (!empty($user->token) && $user->token != $token) {
                    exit();
                }
                //check token
                $data['token'] = $user->token;
                if (empty($data['token'])) {
                    $data['token'] = generate_token();
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
                }
                //send email
                $email_data = array(
                    'template_path' => "email/email_general",
                    'to' => $user->email,
                    'subject' => trans("confirm_your_account"),
                    'email_content' => trans("msg_confirmation_email"),
                    'email_link' => lang_base_url() . "confirm?token=" . $data['token'],
                    'email_button_text' => trans("confirm_your_account")
                );
                $this->load->model("email_model");
                $this->email_model->send_email($email_data);
            }
        }
    }

    //send email activation
    public function send_email_activation_ajax($user_id, $token)
    {
        if (!empty($user_id)) {
            $user = $this->get_user($user_id);
            if (!empty($user)) {
                if (!empty($user->token) && $user->token != $token) {
                    exit();
                }
                //check token
                $data['token'] = $user->token;
                if (empty($data['token'])) {
                    $data['token'] = generate_token();
                    $this->db->where('id', $user->id);
                    $this->db->update('users', $data);
                }

                //send email
                $email_data = array(
                    'email_type' => 'email_general',
                    'to' => $user->email,
                    'subject' => trans("confirm_your_account"),
                    'email_content' => trans("msg_confirmation_email"),
                    'email_link' => lang_base_url() . "confirm?token=" . $data['token'],
                    'email_button_text' => trans("confirm_your_account")
                );
                $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
            }
        }
    }

    //add administrator
    public function add_administrator()
    {
        $this->load->library('bcrypt');

        $data = $this->auth_model->input_values();
        //secure password
        $data['password'] = $this->bcrypt->hash_password($data['password']);
        $data['user_type'] = "registered";
        $data["slug"] = $this->generate_uniqe_slug($data["username"]);
        $data['role'] = "admin";
        $data['banned'] = 0;
        $data['email_status'] = 1;
        $data['token'] = generate_token();
        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('users', $data);
    }

    //update slug
    public function update_slug($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);

        if (empty($user->slug) || $user->slug == "-") {
            $data = array(
                'slug' => "user-" . $user->id,
            );
            $this->db->where('id', $id);
            $this->db->update('users', $data);
        } else {
            if ($this->check_is_slug_unique($user->slug, $id) == true) {
                $data = array(
                    'slug' => $user->slug . "-" . $user->id
                );

                $this->db->where('id', $id);
                $this->db->update('users', $data);
            }
        }
    }

    //logout
    public function logout()
    {
        //unset user data
        $this->session->unset_userdata('modesy_sess_user_id');
        $this->session->unset_userdata('modesy_sess_user_email');
        $this->session->unset_userdata('modesy_sess_user_role');
        $this->session->unset_userdata('modesy_sess_logged_in');
        $this->session->unset_userdata('modesy_sess_app_key');
    }

    //reset password
    public function reset_password($id)
    {
        $id = clean_number($id);
        $this->load->library('bcrypt');
        $new_password = $this->input->post('password', true);
        $data = array(
            'password' => $this->bcrypt->hash_password($new_password),
            'token' => generate_token()
        );
        //change password
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    //delete user
    public function delete_user($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);
        if (!empty($user)) {
            $this->db->where('id', $id);
            return $this->db->delete('users');
        }
        return false;
    }

    //add shop opening requests
    public function add_shop_opening_requests($data)
    {
        if ($this->is_logged_in()) {
            if (empty($data['country_id'])) {
                $data['country_id'] = 0;
            }
            if (empty($data['state_id'])) {
                $data['state_id'] = 0;
            }

            $user = $this->auth_user;
            $this->db->where('id', $user->id);
            return $this->db->update('users', $data);
        }
    }

    //approve shop opening request
    public function approve_shop_opening_request($user_id)
    {
        $user_id = clean_number($user_id);
        if ($this->is_logged_in()) {
            //approve request
            if ($this->input->post('submit', true) == 1) {
                $data = array(
                    'role' => 'vendor',
                    'is_active_shop_request' => 0,
                );
            } else {
                //decline request
                $data = array(
                    'is_active_shop_request' => 2,
                );
            }

            $this->db->where('id', $user_id);
            return $this->db->update('users', $data);
        }
    }

    //update last seen time
    public function update_last_seen()
    {
        if ($this->auth_check) {
            //update last seen
            $data = array(
                'last_seen' => date("Y-m-d H:i:s"),
            );
            $this->db->where('id', $this->auth_user->id);
            $this->db->update('users', $data);
        }
    }

    //is logged in
    public function is_logged_in()
    {
        //check if user logged in
        if ($this->session->userdata('modesy_sess_logged_in') == true && $this->session->userdata('modesy_sess_app_key') == $this->config->item('app_key')) {
            $user = $this->get_user($this->session->userdata('modesy_sess_user_id'));
            if (!empty($user)) {
                if ($user->banned == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    //function get user
    public function get_logged_user()
    {
        if ($this->is_logged_in()) {
            $user_id = $this->session->userdata('modesy_sess_user_id');
            $this->db->where('id', $user_id);
            $query = $this->db->get('users');
            return $query->row();
        }
    }

    //get user by id
    public function get_user($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by email
    public function get_user_by_email($email, $role = null)
    {
        if (!empty($role)) {
            $this->db->where('role', $role);
        }
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by username
    public function get_user_by_username($username)
    {
        $username = remove_special_characters($username);
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by shop name
    public function get_user_by_shop_name($shop_name)
    {
        $shop_name = remove_special_characters($shop_name);
        $this->db->where('shop_name', $shop_name);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by slug
    public function get_user_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get user by token
    public function get_user_by_token($token)
    {
        $token = remove_special_characters($token);
        $this->db->where('token', $token);
        $query = $this->db->get('users');
        return $query->row();
    }

    //get users
    public function get_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    //get users count
    public function get_users_count()
    {
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    //get members
    public function get_members()
    {
        $this->db->where('role', "member");
        $query = $this->db->get('users');
        return $query->result();
    }

    //get vendors
    public function get_vendors()
    {
        $this->db->where('role', "vendor");
        $query = $this->db->get('users');
        return $query->result();
    }

    //get latest members
    public function get_latest_members($limit)
    {
        $limit = clean_number($limit);
        $this->db->limit($limit);
        $this->db->order_by('users.id', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    //get members count
    public function get_members_count()
    {
        $this->db->where('role', "member");
        $query = $this->db->get('users');
        return $query->num_rows();
    }

    //get administrators
    public function get_administrators()
    {
        $this->db->where('role', "admin");
        $query = $this->db->get('users');
        return $query->result();
    }

    public function get_shop($id)
    {
        $column = $this->get_columns("supplier_profiles", "supplier_profiles", ["id" => "supplier_id"]);
        $column = array_merge($column, $this->get_columns("users", "users", ["phone_number" => "number", "city_id" => "unknown_city", "zip_code" => "unknown_zip_code", "address" => "unksa"]));
        // dd($column,$this->get_columns("users", "users"));

        $this->db->select($column)->join("supplier_profiles", "supplier_profiles.user_id = users.id");
        if (!empty($id)) {
            $this->db->where("users.id", $id);
        }
        $query = $this->db->get("users");
        // dd($query->result());
        // dd($query->result());
        return $query->first_row("Shop");
    }

    //get shop opening requests
    public function get_shop_opening_requests()
    {
        $this->db->select("users.username, users.email, users.email_status, users.slug, users.id, users.avatar, users.last_seen, supplier_profiles.phone_number, supplier_profiles.supplier_name, supplier_profiles.nib, supplier_profiles.npwp");
        $this->db->where('is_active_shop_request', 1);
        $query = $this->db->join("supplier_profiles", "supplier_profiles.user_id = users.id")->get('users');
        return $query->result();
    }

    //get last users
    public function get_last_users()
    {
        $this->db->order_by('users.id', 'DESC');
        $this->db->limit(7);
        $query = $this->db->get('users');
        return $query->result();
    }

    //check slug
    public function check_is_slug_unique($slug, $id)
    {
        $id = clean_number($id);
        $this->db->where('users.slug', $slug);
        $this->db->where('users.id !=', $id);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //check if email is unique
    public function is_unique_email($email, $user_id = 0)
    {
        $user_id = clean_number($user_id);
        $user = $this->auth_model->get_user_by_email($email);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //email taken
                return false;
            } else {
                return true;
            }
        }
    }

    //check if username is unique
    public function is_unique_username($username, $user_id = 0)
    {
        $user = $this->get_user_by_username($username);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //username taken
                return false;
            } else {
                return true;
            }
        }
    }

    //check if shop name is unique
    public function is_unique_shop_name($shop_name, $user_id = 0)
    {
        $user = $this->get_user_by_shop_name($shop_name);

        //if id doesnt exists
        if ($user_id == 0) {
            if (empty($user)) {
                return true;
            } else {
                return false;
            }
        }

        if ($user_id != 0) {
            if (!empty($user) && $user->id != $user_id) {
                //shop name taken
                return false;
            } else {
                return true;
            }
        }
    }

    //verify email
    public function verify_email($user)
    {
        if (!empty($user)) {
            $data = array(
                'email_status' => 1,
                'token' => generate_token()
            );
            $this->db->where('id', $user->id);
            return $this->db->update('users', $data);
        }
        return false;
    }

    //ban or remove user ban
    public function ban_remove_ban_user($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);

        if (!empty($user)) {
            $data = array();
            if ($user->banned == 0) {
                $data['banned'] = 1;
            }
            if ($user->banned == 1) {
                $data['banned'] = 0;
            }

            $this->db->where('id', $id);
            return $this->db->update('users', $data);
        }

        return false;
    }

    //open close user shop
    public function open_close_user_shop($id)
    {
        $id = clean_number($id);
        $user = $this->get_user($id);

        if (!empty($user)) {
            $data = array();
            if ($user->role == 'member') {
                $data['role'] = 'vendor';
            } else {
                $data['role'] = 'member';
            }
            $this->db->where('id', $id);
            return $this->db->update('users', $data);
        }

        return false;
    }

    /**
     * Get all column by table.
     *
     * @param string $table
     * @param string $alias
     * @param array $alias_column
     * @return array
     */
    public function get_columns(string $table, string $alias = "", array $alias_column = [])
    {
        $result = [];
        $columns = $this->db->query("SELECT COLUMN_NAME as column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$table}' AND TABLE_SCHEMA = 'u1432237_siplah_jualin' ORDER BY ORDINAL_POSITION")->result();
        if (empty($columns)) {
            return $result;
        }

        foreach ($columns as $key1 => $column) {
            $name = empty($alias) ? $column->column_name : "{$alias}.{$column->column_name}";
            if (!empty($alias_column)) {
                foreach ($alias_column as $key2 => $value) {
                    if ($key2 == $column->column_name) {
                        $name .= " as $value";
                    }
                }
            }
            array_push($result, $name);
        }
        return $result;
    }
}


class Shop
{
    public $supplier_id;
    public $supplier_name;
    public $npwp;
    public $npwp_path;
    public $nib;
    public $nib_path;
    public $phone_number;
    public $is_umkm;
    public $nik;
    public $siup_path;
    public $responsible_person_name;
    public $responsible_person_position;
    public $bank_account;
    public $bank_id;
    public $bank_account_owner_name;
    public $address;
    public $district;
    public $village;
    public $city_id;
    public $zip_code;
    public $legal_status_id;
    public $user_id;
    public $email;
    public $username;
    //new
    public $is_business_entity;
    public $nik_fullname;
    public $ktp_path;
    public $business_type_id;
    public $cover_book_path;
    //Custom

    public function legal_status()
    {
        $arr = ["1" => "Individu", "2" => "PKP", "3" => "Non PKP"];
        return $arr["{$this->legal_status_id}"];
    }

    public function business_type()
    {
        $arr = ["1" => "Micro", "2" => "Kecil", "3" => "Menengah", "4" => "Non Umkm"];
        return $arr["{$this->business_type_id}"];
    }

    public function bank_name()
    {
        $ci = get_instance();
        $ci->load->model("bank_model");
        return $ci->bank_model->get_bank($this->bank_id)->bank_name;
    }

    public function city_name()
    {
        $ci = get_instance();
        $ci->load->model("location_model");
        // dd()
        return $ci->location_model->get_city_by_id($this->city_id)->city_name;
    }

    public function province_id()
    {
        $ci = get_instance();
        $ci->load->model("location_model");
        // dd($this);
        return $ci->location_model->get_city_by_id($this->city_id)->province_id;
    }

    public function province_name()
    {
        $ci = get_instance();
        $ci->load->model("location_model");
        // dd($this->province_id());
        return $ci->location_model->get_province($this->province_id())->province_name;
    }

    public function full_address()
    {
        return "{$this->province_name()}, {$this->city_name()}, {$this->district}, {$this->village}";
    }

    public function nib_ext()
    {
        return $this->extension($this->nib_path);
    }

    public function npwp_ext()
    {
        return $this->extension($this->npwp_path);
    }
    public function siup_ext()
    {
        return $this->extension($this->siup_path);
    }
    public function ktp_ext()
    {
        return $this->extension($this->ktp_path);
    }

    public function extension($path)
    {
        if (empty($path)) {
            return "";
        }
        $arr = explode(".", $path);
        return end($arr);
    }

    public function check_file($name)
    {
        return file_exists(FCPATH . "uploads/supplier_document/{$this->$name}");
    }


    public function __set($name, $value)
    {
        // echo $name;
        // echo $name.":".$value."<br>";
        if ($name === 'legal_status') {
            $arr = ["1" => "Individu", "2" => "PKP", "3" => "Non PKP"];
            $this->legal_status = $arr[(string)$this->legal_status_id];
        }
        if ($name === "city_id") {
            $this->$name = $value;
        }
        if (in_array($name, ["nib_ext", "npwp_ext", "siup_ext"])) {
            $file = explode("_", $name)[0] . "_path";
            $ext = end(explode(".", $file));
            $this->$name = $ext;
        }
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        } else if (method_exists($this, $name)) {
            return $this->$name();
        }
    }
}
