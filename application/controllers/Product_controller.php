<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->review_limit = 5;
        $this->product_per_page = 18;
    }

    /**
     * Start Selling
     */
    public function start_selling()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (is_user_vendor()) {
            redirect(lang_base_url());
        }
        if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(generate_url("settings", "update_profile"));
        }

        $data['title'] = trans("start_selling");
        $data['description'] = trans("start_selling") . " - " . $this->app_name;
        $data['keywords'] = trans("start_selling") . "," . $this->app_name;
        
        $this->load->view('partials/_header', $data);
        $this->load->view('product/start_selling', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Start Selling Post
     */
    public function start_selling_post()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        $user_id = $this->input->post('id', true);
        $data = array(
            'is_active_shop_request' => 1,
            'shop_name' => remove_special_characters($this->input->post('shop_name', true)),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'phone_number' => $this->input->post('phone_number', true),
            'about_me' => $this->input->post('about_me', true)
        );

        //is shop name unique
        if (!$this->auth_model->is_unique_shop_name($data['shop_name'], $user_id)) {
            $this->session->set_flashdata('form_data', $data);
            $this->session->set_flashdata('error', trans("msg_shop_name_unique_error"));
            redirect($this->agent->referrer());
        }

        if ($this->auth_model->add_shop_opening_requests($data)) {
            //send email
            $user = get_user($user_id);
            if (!empty($user) && $this->general_settings->send_email_shop_opening_request == 1) {
                $email_data = array(
                    'email_type' => 'email_general',
                    'to' => $this->general_settings->mail_options_account,
                    'subject' => trans("shop_opening_request"),
                    'email_content' => trans("there_is_shop_opening_request") . "<br>" . trans("user") . ": " . "<strong>" . $user->username . "</strong>",
                    'email_link' => admin_url() . "shop-opening-requests",
                    'email_button_text' => trans("view_details")
                );
                $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
            }

            $this->session->set_flashdata('success', trans("msg_start_selling"));
            redirect($this->agent->referrer());
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Add Product
     */
    public function add_product()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(generate_url("start_selling"));
        }
        if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(generate_url("settings", "update_profile"));
        }

        $data['title'] = trans("sell_now");
        $data['description'] = trans("sell_now") . " - " . $this->app_name;
        $data['keywords'] = trans("sell_now") . "," . $this->app_name;

        $data['modesy_images'] = $this->file_model->get_sess_product_images_array();
        $data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
        $data["active_product_system_array"] = $this->get_activated_product_system();
        

        $this->load->view('partials/_header', $data);
        $this->load->view('product/add_product', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Add Product Post
     */
    public function add_product_post()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(generate_url("settings", "update_profile"));
        }
        //add product
        if ($this->product_model->add_product()) {
            //last id
            $last_id = $this->db->insert_id();
            //update slug
            $this->product_model->update_slug($last_id);
            //add product images
            $this->file_model->add_product_images($last_id);

            redirect(generate_url("sell_now", "product_details") . '/' . $last_id);
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Edit Draft
     */
    public function edit_draft($id)
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        $data["product"] = $this->product_admin_model->get_product($id);
        if (empty($data["product"])) {
            redirect($this->agent->referrer());
        }
        if ($data["product"]->is_draft != 1) {
            redirect($this->agent->referrer());
        }
        if ($data["product"]->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
            redirect($this->agent->referrer());
        }

        $data['title'] = trans("sell_now");
        $data['description'] = trans("sell_now") . " - " . $this->app_name;
        $data['keywords'] = trans("sell_now") . "," . $this->app_name;

        $data['category'] = $this->category_model->get_category($data["product"]->category_id);
        $data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
        $data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
        $data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
        $data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
        $data["active_product_system_array"] = $this->get_activated_product_system();
        

        $this->load->view('partials/_header', $data);
        $this->load->view('product/edit_product');
        $this->load->view('partials/_footer');
    }

    /**
     * Edit Product
     */
    public function edit_product($id)
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        $data["product"] = $this->product_admin_model->get_product($id);
        if (empty($data["product"])) {
            redirect($this->agent->referrer());
        }
        if ($data["product"]->is_deleted == 1) {
            if ($this->auth_user->role != "admin") {
                redirect($this->agent->referrer());
            }
        }
        if ($data["product"]->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
            redirect($this->agent->referrer());
        }

        $data['title'] = trans("edit_product");
        $data['description'] = trans("edit_product") . " - " . $this->app_name;
        $data['keywords'] = trans("edit_product") . "," . $this->app_name;

        $data['category'] = $this->category_model->get_category($data["product"]->category_id);
        $data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
        $data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
        $data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
        $data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
        $data["active_product_system_array"] = $this->get_activated_product_system();
        

        $this->load->view('partials/_header', $data);
        $this->load->view('product/edit_product');
        $this->load->view('partials/_footer');
    }

    /**
     * Edit Product Post
     */
    public function edit_product_post()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        //validate inputs
        $this->form_validation->set_rules('title', trans("title"), 'required|xss_clean|max_length[500]');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('errors', validation_errors());
            redirect($this->agent->referrer());
        } else {
            //product id
            $product_id = $this->input->post('id', true);
            //user id
            $user_id = 0;
            $product = $this->product_admin_model->get_product($product_id);
            if (!empty($product)) {
                $user_id = $product->user_id;
            }
            if ($product->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
                redirect($this->agent->referrer());
            }

            if ($this->product_model->edit_product($product)) {
                //edit slug
                $this->product_model->update_slug($product_id);

                if ($product->is_draft == 1) {
                    redirect(generate_url("sell_now", "product_details") . '/' . $product_id);
                } else {
                    //reset cache
                    reset_cache_data_on_change();
                    reset_user_cache_data($user_id);
                    reset_product_img_cache_data($product_id);

                    $this->session->set_flashdata('success', trans("msg_updated"));
                    redirect($this->agent->referrer());
                }
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
                redirect($this->agent->referrer());
            }
        }
    }

    /**
     * Edit Product Details
     */
    public function edit_product_details($id)
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
            $this->session->set_flashdata('error', trans("msg_confirmed_required"));
            redirect(generate_url("settings", "update_profile"));
        }

        $data['product'] = $this->product_admin_model->get_product($id);
        if (empty($data['product'])) {
            redirect($this->agent->referrer());
        }
        if ($this->auth_user->role != 'admin' && $this->auth_user->id != $data['product']->user_id) {
            redirect($this->agent->referrer());
            exit();
        }

        if ($data['product']->is_draft == 1) {
            $data['title'] = trans("sell_now");
            $data['description'] = trans("sell_now") . " - " . $this->app_name;
            $data['keywords'] = trans("sell_now") . "," . $this->app_name;
        } else {
            $data['title'] = trans("edit_product");
            $data['description'] = trans("edit_product") . " - " . $this->app_name;
            $data['keywords'] = trans("edit_product") . "," . $this->app_name;
        }

        if ($data["product"]->country_id == 0) {
            $data["states"] = $this->location_model->get_states_by_country($this->auth_user->country_id);
        } else {
            $data["states"] = $this->location_model->get_states_by_country($data["product"]->country_id);
        }
        if ($data["product"]->country_id == 0) {
            $data["cities"] = $this->location_model->get_cities_by_state($this->auth_user->state_id);
        } else {
            $data["cities"] = $this->location_model->get_cities_by_state($data["product"]->state_id);
        }

        $data["custom_field_array"] = $this->field_model->generate_custom_fields_array($data["product"]->category_id, $data["product"]->id);
        $data["product_variations"] = $this->variation_model->get_product_variations($data["product"]->id);
        $data["user_variations"] = $this->variation_model->get_variation_by_user_id($data["product"]->user_id);
        $data['form_settings'] = $this->settings_model->get_form_settings();
        $data['license_keys'] = $this->product_model->get_license_keys($data["product"]->id);

        $this->load->view('partials/_header', $data);
        $this->load->view('product/edit_product_details');
        $this->load->view('partials/_footer');
    }

    /**
     * Edit Product Details Post
     */
    public function edit_product_details_post()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        $product_id = $this->input->post('id', true);
        $product = $this->product_admin_model->get_product($product_id);
        if (empty($product)) {
            redirect($this->agent->referrer());
            exit();
        }
        if ($this->auth_user->role != 'admin' && $this->auth_user->id != $product->user_id) {
            redirect($this->agent->referrer());
            exit();
        }

        if ($this->product_model->edit_product_details($product_id)) {
            //edit custom fields
            $this->product_model->update_product_custom_fields($product_id);

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($this->auth_user->id);

            if ($product->is_draft != 1) {
                $this->session->set_flashdata('success', trans("msg_updated"));
                redirect($this->agent->referrer());
            } else {
                //send email
                if ($this->general_settings->send_email_new_product == 1) {
                    $email_data = array(
                        'email_type' => 'new_product',
                        'product_id' => $product->id
                    );
                    $this->session->set_userdata('mds_send_email_data', json_encode($email_data));
                }

                //if draft
                if ($this->input->post('submit', true) == 'save_as_draft') {
                    redirect(generate_url("drafts"));
                    exit();
                }
                if ($this->general_settings->promoted_products == 1) {
                    redirect(generate_url("promote_product", "pricing") . "/" . $product_id . "?type=new");
                } else {
                    redirect(generate_product_url($product));
                }
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            redirect($this->agent->referrer());
        }
    }

    /**
     * Delete Product
     */
    public function delete_product()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        $id = $this->input->post('id', true);

        //user id
        $user_id = 0;
        $product = $this->product_admin_model->get_product($id);
        if (!empty($product)) {
            $user_id = $product->user_id;
        }

        if ($this->auth_user->role == "admin" || $this->auth_user->id == $user_id) {
            if ($this->product_model->delete_product($id)) {
                $this->session->set_flashdata('success', trans("msg_product_deleted"));
            } else {
                $this->session->set_flashdata('error', trans("msg_error"));
            }

            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($user_id);
        }
    }

    /**
     * Delete Draft
     */
    public function delete_draft()
    {
        //check auth
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }
        $id = $this->input->post('id', true);

        //user id
        $user_id = 0;
        $product = $this->product_admin_model->get_product($id);
        if (!empty($product)) {
            $user_id = $product->user_id;
        }

        if ($this->auth_user->role == "admin" || $this->auth_user->id == $user_id) {
            $this->product_admin_model->delete_product_permanently($id);
            //reset cache
            reset_cache_data_on_change();
            reset_user_cache_data($user_id);
        }
    }


    //add review
    public function add_review_post()
    {
        if ($this->auth_check && $this->general_settings->reviews == 1) {
            $rating = $this->input->post('rating', true);
            $product_id = $this->input->post('product_id', true);
            $review_text = $this->input->post('review', true);
            $product = $this->product_model->get_product_by_id($product_id);
            if ($product->user_id != $this->auth_user->id) {
                $review = $this->review_model->get_review($product_id, $this->auth_user->id);
                if (!empty($review)) {
                    $this->review_model->update_review($review->id, $rating, $product_id, $review_text);
                } else {
                    $this->review_model->add_review($rating, $product_id, $review_text);
                }
            }
        }
        redirect($this->agent->referrer());
    }

    //load more review
    public function load_more_review()
    {
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->review_limit;
        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["reviews"] = $this->review_model->get_limited_reviews($product_id, $new_limit);
        $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
        $data['review_limit'] = $new_limit;

        $this->load->view('product/details/_make_review', $data);
    }

    //delete review
    public function delete_review()
    {
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $user_id = $this->input->post('user_id', true);
        $limit = $this->input->post('limit', true);

        $review = $this->review_model->get_review($product_id, $user_id);
        if ($this->auth_check && !empty($review)) {
            if ($this->auth_user->role == "admin" || $this->auth_user->id == $review->user_id) {
                $this->review_model->delete_review($id, $product_id);
            }
        }

        $data["product"] = $this->product_model->get_product_by_id($product_id);
        $data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
        $data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
        $data['review_limit'] = $limit;

        $this->load->view('product/details/_make_review', $data);
    }

    //show address on map
    public function show_address_on_map()
    {
        $country_text = $this->input->post('country_text', true);
        $country_val = $this->input->post('country_val', true);
        $state_text = $this->input->post('state_text', true);
        $state_val = $this->input->post('state_val', true);
        $address = $this->input->post('address', true);
        $zip_code = $this->input->post('zip_code', true);

        $adress_details = $address . " " . $zip_code;
        $data["map_address"] = "";
        if (!empty($adress_details)) {
            $data["map_address"] = $adress_details . " ";
        }
        if (!empty($state_val)) {
            $data["map_address"] = $data["map_address"] . $state_text . " ";
        }
        if (!empty($country_val)) {
            $data["map_address"] = $data["map_address"] . $country_text;
        }

        $this->load->view('product/_load_map', $data);
    }

    //get activated product system
    public function get_activated_product_system()
    {
        $array = array(
            'active_system_count' => 0,
            'active_system_value' => "",
        );
        if ($this->general_settings->marketplace_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "sell_on_site";
        }
        if ($this->general_settings->classified_ads_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "ordinary_listing";
        }
        if ($this->general_settings->bidding_system == 1) {
            $array['active_system_count'] = $array['active_system_count'] + 1;
            $array['active_system_value'] = "bidding";
        }
        return $array;
    }

    /*
    *------------------------------------------------------------------------------------------
    * LICENSE KEYS
    *------------------------------------------------------------------------------------------
    */
    //add license keys
    public function add_license_keys()
    {
        post_method();
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);

        if (!empty($product)) {
            if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
                $this->product_model->add_license_keys($product_id);
                $this->session->set_flashdata('success', trans("msg_add_license_keys"));
                $data = array(
                    'result' => 1,
                    'success_message' => $this->load->view('partials/_messages', '', true)
                );
                echo json_encode($data);
                reset_flash_data();
            }
        }
    }

    //delete license key
    public function delete_license_key()
    {
        post_method();
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $product = $this->product_model->get_product_by_id($product_id);
        if (!empty($product)) {
            if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
                $this->product_model->delete_license_key($id);
            }
        }
    }

    //refresh license keys list
    public function refresh_license_keys_list()
    {
        post_method();
        $product_id = $this->input->post('product_id', true);
        $data['product'] = $this->product_model->get_product_by_id($product_id);
        if (!empty($data['product'])) {
            if ($this->auth_user->id == $data['product']->user_id || $this->auth_user->role == 'admin') {
                $data['license_keys'] = $this->product_model->get_license_keys($product_id);
                $this->load->view("product/license/_license_keys_list", $data);
            }
        }
    }
}
