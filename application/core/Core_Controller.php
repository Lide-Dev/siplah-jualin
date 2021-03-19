<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //general settings
        $this->general_settings = $this->settings_model->get_general_settings();
        //routes
        $this->routes = $this->settings_model->get_routes();
        //storage settings
        $this->storage_settings = $this->settings_model->get_storage_settings();
        //form settings
        $this->form_settings = $this->settings_model->get_form_settings();
        //payment settings
        $this->payment_settings = $this->settings_model->get_payment_settings();
        //set timezone
        date_default_timezone_set($this->general_settings->timezone);
        //languages
        $this->languages = $this->config->item('languages');
        //site lang
        $this->site_lang = $this->language_model->get_site_language();
        //selected lang
        $this->selected_lang = $this->site_lang;
        //rtl
        $this->rtl = false;
        //language base url
        $this->lang_base_url = base_url();
        //set language
        $lang_segment = $this->uri->segment(1);
        foreach ($this->languages as $lang) {
            if ($lang_segment == $lang->short_form) {
                if ($this->general_settings->multilingual_system == 1):
                    $this->selected_lang = $lang;
                else:
                    redirect(base_url());
                endif;
            }
        }
        //set lang base url
        if ($this->general_settings->site_lang == $this->selected_lang->id) {
            $this->lang_base_url = base_url();
        } else {
            $this->lang_base_url = base_url() . $this->selected_lang->short_form . "/";
        }
        //language translations
        $this->language_translations = $this->get_translation_array();
        //currencies
        $this->currencies = $this->get_currencies_array();
        //countries
        $this->countries = $this->location_model->get_active_countries();
        //check auth
        $this->auth_check = auth_check();
        if ($this->auth_check) {
            $this->auth_user = user();
        }
        //settings
        $this->settings = $this->settings_model->get_settings($this->selected_lang->id);
        //get site fonts
        $this->fonts = $this->settings_model->get_selected_fonts();
        //application name
        $this->app_name = $this->general_settings->application_name;
        //aws base url
        $this->aws_base_url = $this->storage_settings->aws_base_url . $this->storage_settings->aws_bucket . "/";
        if (empty($this->storage_settings->aws_bucket)) {
            $this->aws_base_url = $this->storage_settings->aws_base_url;
        }
        //check promoted posts
        $this->product_model->check_promoted_products();
        //variables
        $this->username_maxlength = 40;
        $this->thousands_separator = '.';
        $this->input_initial_price = '0.00';
        if ($this->payment_settings->currency_format == 'european') {
            $this->thousands_separator = ',';
            $this->input_initial_price = '0,00';
        }

        //default location
        $this->default_location_id = $this->location_model->get_default_location();

        //update last seen time
        $this->auth_model->update_last_seen();
        $this->product_paginate_per_page = 15;
        $this->menu_subcategory_display_limit = 6;
    }

    public function get_translation_array()
    {
        $translations = $this->language_model->get_language_translations($this->selected_lang->id);
        $array = array();
        if (!empty($translations)) {
            foreach ($translations as $translation) {
                $array[$translation->label] = $translation->translation;
            }
        }
        return $array;
    }

    public function get_currencies_array()
    {
        $currencies = $this->currency_model->get_currencies();
        $array = array();
        if (!empty($currencies)) {
            foreach ($currencies as $currency) {
                $array[$currency->code] = array('name' => $currency->name, 'symbol' => $currency->symbol, 'hex' => $currency->hex);
            }
        }
        return $array;
    }
}

class Home_Core_Controller extends Core_Controller
{
    public function __construct()
    {
        parent::__construct();

        //maintenance mode
        if ($this->general_settings->maintenance_mode_status == 1) {
            if (!is_admin()) {
                $this->maintenance_mode();
            }
        }

        if ($this->input->method() == "post") {
            //set post language
            $lang_id = $this->input->post('sys_lang_id', true);
            if (!empty($lang_id)) {
                $this->selected_lang = $this->language_model->get_language($lang_id);
                $this->language_translations = $this->get_translation_array();
                if ($this->general_settings->site_lang == $lang_id) {
                    $this->lang_base_url = base_url();
                } else {
                    $this->lang_base_url = base_url() . $this->selected_lang->short_form . "/";
                }
            }
        }

        $this->menu_links = $this->page_model->get_menu_links($this->selected_lang->id);

        $this->categories = $this->category_model->get_categories();
        $this->parent_categories = get_parent_categories($this->categories);

        //recaptcha status
        $global_data['recaptcha_status'] = true;
        if (empty($this->general_settings->recaptcha_site_key) || empty($this->general_settings->recaptcha_secret_key)) {
            $global_data['recaptcha_status'] = false;
        }
        $this->recaptcha_status = $global_data['recaptcha_status'];

        if ($this->auth_check) {
            $global_data['unread_message_count'] = get_unread_conversations_count($this->auth_user->id);
        } else {
            $global_data['unread_message_count'] = 0;
        }

        $this->load->vars($global_data);
    }

    //maintenance mode
    public function maintenance_mode()
    {
        $this->load->view('maintenance');
    }

    //verify recaptcha
    public function recaptcha_verify_request()
    {
        if (!$this->recaptcha_status) {
            return true;
        }

        $this->load->library('recaptcha');
        $recaptcha = $this->input->post('g-recaptcha-response');
        if (!empty($recaptcha)) {
            $response = $this->recaptcha->verifyResponse($recaptcha);
            if (isset($response['success']) && $response['success'] === true) {
                return true;
            }
        }
        return false;
    }

    public function paginate($url, $total_rows, $per_page)
    {
        //initialize pagination
        $page = $this->security->xss_clean($this->input->get('page'));
        $page = clean_number($page);
        if (empty($page) || $page <= 0) {
            $page = 0;
        }

        if ($page != 0) {
            $page = $page - 1;
        }

        $config['num_links'] = 4;
        $config['base_url'] = $url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        $per_page = clean_number($per_page);

        return array('per_page' => $per_page, 'offset' => $page * $per_page, 'current_page' => $page + 1);
    }
}

class Admin_Core_Controller extends Core_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function paginate($url, $total_rows)
    {
        //initialize pagination
        $page = $this->security->xss_clean($this->input->get('page'));
        $per_page = $this->input->get('show', true);
        $page = clean_number($page);
        if (empty($page) || $page <= 0) {
            $page = 0;
        }

        if ($page != 0) {
            $page = $page - 1;
        }

        if (empty($per_page)) {
            $per_page = 15;
        }
        $config['num_links'] = 4;
        $config['base_url'] = $url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);

        return array('per_page' => $per_page, 'offset' => $page * $per_page);
    }
}

