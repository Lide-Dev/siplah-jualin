<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Language_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'name' => $this->input->post('name', true),
            'short_form' => $this->input->post('short_form', true),
            'language_code' => $this->input->post('language_code', true),
            'language_order' => $this->input->post('language_order', true),
            'text_direction' => "ltr",
            'ckeditor_lang' => $this->input->post('ckeditor_lang', true),
            'status' => $this->input->post('status', true)
        );
        return $data;
    }

    //add language
    public function add_language()
    {
        $data = $this->input_values();

        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $data["flag_path"] = $this->upload_model->flag_upload($temp_path);
        } else {
            $data["flag_path"] = "";
        }

        if ($this->db->insert('languages', $data)) {
            $language_id = $this->db->insert_id();
            //insert translations
            $translations = $this->get_language_translations(1);
            if (!empty($translations)) {
                foreach ($translations as $translation) {
                    $data_translation = array(
                        'lang_id' => $language_id,
                        'label' => $translation->label,
                        'translation' => $translation->translation
                    );
                    $this->db->insert('language_translations', $data_translation);
                }
            }
            return $language_id;
        }
        return false;
    }

    //add language settings
    public function add_language_settings($lang_id)
    {
        //add settings
        $settings = array(
            'lang_id' => $lang_id,
            'site_font' => 19,
            'site_title' => "Modesy",
            'homepage_title' => "Index",
            'site_description ' => "Modesy",
            'keywords' => "modesy",
            'facebook_url' => "",
            'twitter_url' => "",
            'instagram_url' => "",
            'pinterest_url' => "",
            'linkedin_url' => "",
            'vk_url' => "",
            'youtube_url' => "",
            'about_footer' => "",
            'contact_text' => "",
            'contact_address' => "",
            'contact_email' => "",
            'contact_phone' => "",
            'copyright' => "",
            'cookies_warning' => 1,
            'cookies_warning_text' => "This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies.",
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('settings', $settings);
    }

    //add language pages
    public function add_language_pages($lang_id)
    {
        $page_terms = array(
            'lang_id' => $lang_id,
            'title' => "Terms & Conditions",
            'slug' => "terms-conditions",
            'description' => "Terms & Conditions Page",
            'keywords' => "Terms, Conditions, Page",
            'page_content' => "",
            'page_order' => 1,
            'visibility' => 1,
            'title_active' => 1,
            'location' => "information",
            'is_custom' => 0,
            'page_default_name' => "terms_conditions",
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('pages', $page_terms);

        $page_contact = array(
            'lang_id' => $lang_id,
            'title' => "Contact",
            'slug' => "contact",
            'description' => "Contact Page",
            'keywords' => "Contact, Page",
            'page_content' => "",
            'page_order' => 1,
            'visibility' => 1,
            'title_active' => 1,
            'location' => "top_menu",
            'is_custom' => 0,
            'page_default_name' => "contact",
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('pages', $page_contact);

        $page_blog = array(
            'lang_id' => $lang_id,
            'title' => "Blog",
            'slug' => "blog",
            'description' => "Blog Page",
            'keywords' => "Blog, Page",
            'page_content' => "",
            'page_order' => 1,
            'visibility' => 1,
            'title_active' => 1,
            'location' => "quick_links",
            'is_custom' => 0,
            'page_default_name' => "blog",
            'created_at' => date('Y-m-d H:i:s')
        );
        $this->db->insert('pages', $page_blog);

    }

    //update language
    public function update_language($id)
    {
        $language = $this->get_language($id);
        if (!empty($language)) {
            $data = $this->input_values();

            $this->load->model('upload_model');
            $temp_path = $this->upload_model->upload_temp_image('file');
            if (!empty($temp_path)) {
                delete_file_from_server($language->flag_path);
                $data["flag_path"] = $this->upload_model->flag_upload($temp_path);
            }

            $this->db->where('id', clean_number($id));
            return $this->db->update('languages', $data);
        }
    }

    //get language
    public function get_language($id)
    {
        $sql = "SELECT * FROM languages WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get site language
    public function get_site_language()
    {
        $sql = "SELECT * FROM languages WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($this->general_settings->site_lang)));
        $row = $query->row();
        if (empty($row)) {
            $query = $this->db->query("SELECT * FROM languages ORDER BY id LIMIT 1");
            $row = $query->row();
        }
        return $row;
    }

    //get languages
    public function get_languages()
    {
        $query = $this->db->query("SELECT * FROM languages ORDER BY language_order");
        return $query->result();
    }

    //get language translations
    public function get_language_translations($lang_id)
    {
        $sql = "SELECT * FROM language_translations WHERE lang_id = ?";
        $query = $this->db->query($sql, array(clean_number($lang_id)));
        return $query->result();
    }

    //get paginated translations
    public function get_paginated_translations($lang_id, $per_page, $offset)
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $like = '%' . $q . '%';
            $sql = "SELECT * FROM language_translations WHERE lang_id = ? AND (label LIKE ? OR translation LIKE ?) ORDER BY id LIMIT ?, ?";
            $query = $this->db->query($sql, array(clean_number($lang_id), $like, $like, clean_number($offset), clean_number($per_page)));
        } else {
            $sql = "SELECT * FROM language_translations WHERE lang_id = ? ORDER BY id LIMIT ?, ?";
            $query = $this->db->query($sql, array(clean_number($lang_id), clean_number($offset), clean_number($per_page)));
        }
        return $query->result();
    }

    //get translations count
    public function get_translation_count($lang_id)
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $like = '%' . $q . '%';
            $sql = "SELECT * FROM language_translations WHERE lang_id = ? AND (label LIKE ? OR translation LIKE ?)";
            $query = $this->db->query($sql, array(clean_number($lang_id), $like, $like));
        } else {
            $sql = "SELECT * FROM language_translations WHERE lang_id = ?";
            $query = $this->db->query($sql, array(clean_number($lang_id)));
        }
        return $query->num_rows();
    }

    //get active languages
    public function get_active_languages()
    {
        $query = $this->db->query("SELECT * FROM languages WHERE status = 1 ORDER BY language_order");
        return $query->result();
    }

    //set language
    public function set_language()
    {
        $data = array(
            'site_lang' => $this->input->post('site_lang', true),
        );

        $lang = $this->language_model->get_language($data["site_lang"]);

        if (!empty($lang)) {
            $this->db->where('id', 1);
            return $this->db->update('general_settings', $data);
        }

        return false;
    }

    //delete language
    public function delete_language($id)
    {
        $language = $this->get_language($id);
        if (!empty($language)) {
            //delete translations
            $sql = "SELECT * FROM language_translations WHERE lang_id = ?";
            $query = $this->db->query($sql, array(clean_number($language->id)));
            $translations = $query->result();
            if (!empty($translations)) {
                foreach ($translations as $translation) {
                    $this->db->where('id', $translation->id);
                    $this->db->delete('language_translations');
                }
            }
            //delete flag
            delete_file_from_server($language->flag_path);
            //delete language
            $this->db->where('id', $id);
            return $this->db->delete('languages');
        } else {
            return false;
        }
    }

    //update translation
    public function update_translation($lang_id, $id, $translation)
    {
        $data = array(
            'translation' => $translation
        );
        $this->db->where('lang_id', clean_number($lang_id));
        $this->db->where('id', clean_number($id));
        $this->db->update('language_translations', $data);
    }
}
