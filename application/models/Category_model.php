<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'slug' => $this->input->post('slug', true),
            'title_meta_tag' => $this->input->post('title_meta_tag', true),
            'description' => $this->input->post('description', true),
            'keywords' => $this->input->post('keywords', true),
            'category_order' => $this->input->post('category_order', true),
            'homepage_order' => $this->input->post('homepage_order', true),
            'visibility' => $this->input->post('visibility', true),
            'show_on_homepage' => $this->input->post('show_on_homepage', true),
            'show_image_on_navigation' => $this->input->post('show_image_on_navigation', true)
        );
        return $data;
    }

    //add category
    public function add_category()
    {
        $data = $this->input_values();
        //set slug
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($this->input->post('name_lang_' . $this->general_settings->site_lang, true));
        } else {
            $data["slug"] = remove_special_characters($data["slug"], true);
        }

        //set parent id
        $data["parent_id"] = 0;
        $category_ids_array = $this->input->post('parent_id', true);
        if (!empty($category_ids_array)) {
            foreach ($category_ids_array as $key => $value) {
                if (!empty($value)) {
                    $data["parent_id"] = $value;
                }
            }
        }

        $data["storage"] = "local";
        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        $data['image'] = "";
        if (!empty($temp_path)) {
            $data["image"] = $this->upload_model->category_image_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);
        }
        //move to s3
        if ($this->storage_settings->storage == "aws_s3") {
            $this->load->model("aws_model");
            $data["storage"] = "aws_s3";
            //move image
            if ($data["image"] != "") {
                $this->aws_model->put_category_object($data["image"], FCPATH . $data["image"]);
                delete_file_from_server($data["image"]);
            }
        }

        $data['created_at'] = date('Y-m-d H:i:s');

        return $this->db->insert('categories', $data);
    }

    //add category name
    public function add_category_name($category_id)
    {
        foreach ($this->languages as $language) {
            $data = array(
                'category_id' => clean_number($category_id),
                'lang_id' => $language->id,
                'name' => $this->input->post('name_lang_' . $language->id, true)
            );
            $this->db->insert('categories_lang', $data);
        }
    }

    //update slug
    public function update_slug($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            if (empty($category->slug) || $category->slug == "-") {
                $data = array(
                    'slug' => $category->id
                );
                $this->db->where('id', $category->id);
                return $this->db->update('categories', $data);
            } else {
                if (!empty($this->check_category_slug($category->slug, $id))) {
                    $data = array(
                        'slug' => $category->slug . "-" . $category->id
                    );
                    $this->db->where('id', $category->id);
                    return $this->db->update('categories', $data);
                }
            }
        }
    }

    //update category
    public function update_category($id)
    {
        $data = $this->input_values();
        //set slug
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($this->input->post('name_lang_' . $this->general_settings->site_lang, true));
        } else {
            $data["slug"] = remove_special_characters($data["slug"], true);
        }

        //set parent id
        $data["parent_id"] = 0;
        $category_ids_array = $this->input->post('parent_id', true);
        if (!empty($category_ids_array)) {
            foreach ($category_ids_array as $key => $value) {
                if (!empty($value)) {
                    $data["parent_id"] = $value;
                }
            }
        }

        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $data["image"] = $this->upload_model->category_image_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);
            $category = $this->get_category($id);
            $data["storage"] = "local";
            //move to s3
            if ($this->storage_settings->storage == "aws_s3") {
                $this->load->model("aws_model");
                $data["storage"] = "aws_s3";
                //move image
                $this->aws_model->put_category_object($data["image"], FCPATH . $data["image"]);
                delete_file_from_server($data["image"]);
            }
            //delete old images
            if ($category->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_category_object($category->image);
            } else {
                delete_file_from_server($category->image);
            }
        }

        $this->db->where('id', clean_number($id));
        return $this->db->update('categories', $data);
    }

    //update category name
    public function update_category_name($category_id)
    {
        foreach ($this->languages as $language) {
            $data = array(
                'category_id' => clean_number($category_id),
                'lang_id' => $language->id,
                'name' => $this->input->post('name_lang_' . $language->id, true)
            );
            //check category name exists
            $this->db->where('category_id', clean_number($category_id));
            $this->db->where('lang_id', $language->id);
            $row = $this->db->get('categories_lang')->row();
            if (empty($row)) {
                $this->db->insert('categories_lang', $data);
            } else {
                $this->db->where('category_id', clean_number($category_id));
                $this->db->where('lang_id', $language->id);
                $this->db->update('categories_lang', $data);
            }
        }
    }

    //check category slug
    public function check_category_slug($slug, $id)
    {
        $sql = "SELECT * FROM categories WHERE categories.slug = ? AND categories.id != ?";
        $query = $this->db->query($sql, array(clean_str($slug), clean_number($id)));
        return $query->row();
    }

    //build category query
    public function build_query($lang_id = null)
    {
        if (empty($lang_id)) {
            $lang_id = $this->selected_lang->id;
        }
        $ci =& get_instance();
        if (item_count($ci->languages) > 1) {
            return "SELECT categories.*, categories.parent_id AS join_parent_id, categories_lang.name AS name,
                (SELECT slug FROM categories WHERE id = join_parent_id) AS parent_slug,
                (SELECT name FROM categories_lang WHERE categories_lang.category_id = categories.id AND categories_lang.lang_id != " . $this->db->escape(clean_number($lang_id)) . "  ORDER BY categories_lang.lang_id LIMIT 1) AS second_name
                FROM categories
                LEFT JOIN categories_lang ON categories.id = categories_lang.category_id AND categories_lang.lang_id = " . $this->db->escape(clean_number($lang_id)) . " ";
        } else {
            return "SELECT categories.*, categories.parent_id AS join_parent_id, categories_lang.name AS name,
                (SELECT slug FROM categories WHERE id = join_parent_id) AS parent_slug
                FROM categories
                LEFT JOIN categories_lang ON categories.id = categories_lang.category_id AND categories_lang.lang_id = " . $this->db->escape(clean_number($lang_id)) . " ";
        }
    }

    //get category
    public function get_category($id)
    {
        $sql = $this->build_query() . "WHERE categories.id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get category by lang
    public function get_category_by_lang($id, $lang_id)
    {
        $sql = $this->build_query($lang_id) . "WHERE categories.id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get paginated categories
    public function get_paginated_categories($per_page, $offset)
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $like = '%' . $q . '%';
            $sql = $this->build_query() . "WHERE categories_lang.name LIKE ? ORDER BY categories.created_at DESC LIMIT ?, ?";
            $query = $this->db->query($sql, array($like, clean_number($offset), clean_number($per_page)));
        } else {
            $sql = $this->build_query() . "ORDER BY categories.created_at DESC LIMIT ?, ?";
            $query = $this->db->query($sql, array(clean_number($offset), clean_number($per_page)));
        }
        return $query->result();
    }

    //get categories count
    public function get_categories_count()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $like = '%' . $q . '%';
            $sql = $this->build_query() . "WHERE categories_lang.name LIKE ?";
            $query = $this->db->query($sql, array($like));
        } else {
            $sql = $this->build_query();
            $query = $this->db->query($sql);
        }
        return $query->num_rows();
    }

    //get categories
    public function get_categories()
    {
        $sql = $this->build_query() . "WHERE categories.visibility = 1 ORDER BY category_order";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all categories
    public function get_all_categories()
    {
        $sql = $this->build_query() . "ORDER BY category_order";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get mobile menu categories
    public function get_mobile_menu_categories()
    {
        $lang_id = $this->selected_lang->id;
        $ci =& get_instance();
        if (item_count($ci->languages) > 1) {
            $sql = "SELECT categories.*, categories.parent_id AS join_parent_id, categories_lang.name AS name,
                (SELECT slug FROM categories WHERE id = join_parent_id) AS parent_slug,
                (SELECT name FROM categories_lang WHERE categories_lang.category_id = categories.id AND categories_lang.lang_id != " . $this->db->escape(clean_number($lang_id)) . "  ORDER BY categories_lang.lang_id LIMIT 1) AS second_name,
                (SELECT id FROM categories AS sub_categories WHERE sub_categories.parent_id = categories.id LIMIT 1) AS has_subcategory
                FROM categories
                LEFT JOIN categories_lang ON categories.id = categories_lang.category_id AND categories_lang.lang_id = " . $this->db->escape(clean_number($lang_id)) . " ";
        } else {
            $sql = "SELECT categories.*, categories.parent_id AS join_parent_id, categories_lang.name AS name,
                (SELECT slug FROM categories WHERE id = join_parent_id) AS parent_slug,
                (SELECT id FROM categories AS sub_categories WHERE sub_categories.parent_id = categories.id LIMIT 1) AS has_subcategory
                FROM categories
                LEFT JOIN categories_lang ON categories.id = categories_lang.category_id AND categories_lang.lang_id = " . $this->db->escape(clean_number($lang_id)) . " ";
        }
        $sql = $sql . "ORDER BY category_order";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all categories ordered by name
    public function get_categories_ordered_by_name()
    {
        $sql = $this->build_query() . "WHERE categories.visibility = 1 ORDER BY categories_lang.name";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all parent categories
    public function get_all_parent_categories()
    {
        $sql = $this->build_query() . "WHERE categories.parent_id = 0 ORDER BY category_order";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get subcategories by parent id
    public function get_subcategories_by_parent_id($parent_id)
    {
        $sql = $this->build_query() . "WHERE categories.parent_id = ? ORDER BY category_order";
        $query = $this->db->query($sql, array(clean_number($parent_id)));
        return $query->result();
    }

    //get subcategories by parent id except one
    public function get_subcategories_by_parent_id_except_one($parent_id, $except_id)
    {
        $sql = $this->build_query() . "WHERE categories.id != ? AND categories.parent_id = ? AND categories.visibility = 1 ORDER BY category_order";
        $query = $this->db->query($sql, array(clean_number($except_id), clean_number($parent_id)));
        return $query->result();
    }

    //get subcategories by parent id by lang
    public function get_subcategories_by_parent_id_by_lang($parent_id, $lang_id)
    {
        $sql = $this->build_query($lang_id) . "WHERE categories.parent_id = ? AND categories.visibility = 1 ORDER BY category_order";
        $query = $this->db->query($sql, array(clean_number($parent_id)));
        return $query->result();
    }

    //get category by slug
    public function get_category_by_slug($slug)
    {
        $sql = $this->build_query() . "WHERE categories.slug = ? AND categories.visibility = 1 ORDER BY id LIMIT 1";
        $query = $this->db->query($sql, array(clean_str($slug)));
        return $query->row();
    }

    //get parent category by slug
    public function get_parent_category_by_slug($slug)
    {
        $sql = $this->build_query() . "WHERE categories.slug = ? AND categories.visibility = 1 AND parent_id = 0 ORDER BY id LIMIT 1";
        $query = $this->db->query($sql, array(clean_str($slug)));
        return $query->row();
    }

    //get featured categories
    public function get_featured_categories()
    {
        $sql = $this->build_query() . "WHERE categories.show_on_homepage = 1 AND categories.visibility = 1 ORDER BY homepage_order";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get categories json
    public function get_categories_json($lang_id)
    {
        $categories = $this->get_categories();
        $array = array();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $item = array(
                    'id' => $category->id,
                    'parent_id' => $category->parent_id,
                    'name' => category_name($category),
                );
                array_push($array, $item);
            }
        }
        echo json_encode($array);
    }

    //get parent categories array by category id
    public function get_parent_categories_array_by_category_id($category_id)
    {
        $array_categories = array();
        $category = $this->get_category($category_id);
        if (!empty($category)) {
            array_push($array_categories, $category);
            for ($i = 0; $i < 50; $i++) {
                $parent = $this->get_category($category->parent_id);
                if (!empty($parent)) {
                    array_push($array_categories, $parent);
                    $category = $parent;
                    if ($category->parent_id == 0) {
                        break;
                    }
                }
            }
        }
        return array_reverse($array_categories);
    }

    //get parent categories ids array by category id
    public function get_parent_categories_ids_array_by_category_id($category_id)
    {
        $categories_array = $this->get_parent_categories_array_by_category_id($category_id);
        $array_ids = array();
        if (!empty($categories_array)) {
            foreach ($categories_array as $item) {
                array_push($array_ids, $item->id);
            }
        }
        return $array_ids;
    }

    //get parent category tree ids string (All parent categories will be returned)
    public function get_parent_category_tree_ids_string($category_id)
    {
        return generate_ids_string($this->get_parent_categories_ids_array_by_category_id($category_id));
    }

    //get category tree ids array (All subcategories will be returned)
    public function get_category_tree_ids_array($parent_id)
    {
        $ids = array();
        array_push($ids, clean_number($parent_id));
        $sql = "SELECT id FROM categories WHERE parent_id = ?";
        $query = $this->db->query($sql, array(clean_number($parent_id)));
        $rows = $query->result();
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $ids = array_merge($ids, $this->get_category_tree_ids_array($row->id));
            }
        }
        return $ids;
    }

    //get category tree ids string (All subcategories will be returned)
    public function get_category_tree_ids_string($parent_id)
    {
        return generate_ids_string($this->get_category_tree_ids_array($parent_id));
    }

    //delete category name
    public function delete_category_name($category_id)
    {
        $this->db->where('category_id', clean_number($category_id));
        $query = $this->db->get('categories_lang');
        $results = $query->result();
        if (!empty($results)) {
            foreach ($results as $result) {
                $this->db->where('id', $result->id);
                $this->db->delete('categories_lang');
            }
        }
    }

    //delete category image
    public function delete_category_image($category_id)
    {
        $category = $this->get_category($category_id);
        if (!empty($category)) {
            delete_file_from_server($category->image);
            $data = array(
                'image' => ""
            );
            $this->db->where('id', $category->id);
            return $this->db->update('categories', $data);
        }
    }

    //delete category
    public function delete_category($id)
    {
        $category = $this->get_category($id);
        if (!empty($category)) {
            //delete from s3
            if ($category->storage == "aws_s3") {
                $this->load->model("aws_model");
                if (!empty($category->image)) {
                    $this->aws_model->delete_category_object($category->image);
                }
            } else {
                delete_file_from_server($category->image);
            }
            //delete category name
            $this->delete_category_name($category->id);
            $this->db->where('id', $category->id);
            return $this->db->delete('categories');
        }
        return false;
    }
}
