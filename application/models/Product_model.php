<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends Core_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //add product
    public function add_product()
    {
        $data = array(
            'title' => $this->input->post('title', true),
            'product_type' => $this->input->post('product_type', true),
            'listing_type' => $this->input->post('listing_type', true),
            'sku' => $this->input->post('sku', true),
            'price' => 0,
            'currency' => "",
            'discount_rate' => 0,
            'vat_rate' => 0,
            'description' => $this->input->post('description', false),
            'product_condition' => "",
            'country_id' => 0,
            'state_id' => 0,
            'city_id' => 0,
            'address' => "",
            'zip_code' => "",
            'user_id' => $this->auth_user->id,
            'status' => 0,
            'is_promoted' => 0,
            'promote_start_date' => date('Y-m-d H:i:s'),
            'promote_end_date' => date('Y-m-d H:i:s'),
            'promote_plan' => "none",
            'promote_day' => 0,
            'visibility' => 1,
            'rating' => 0,
            'hit' => 0,
            'demo_url' => "",
            'external_link' => "",
            'files_included' => "",
            'stock' => 1,
            'shipping_time' => "",
            'shipping_cost_type' => "",
            'shipping_cost' => 0,
            'shipping_cost_additional' => 0,
            'is_deleted' => 0,
            'is_draft' => 1,
            'is_free_product' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        $data["slug"] = str_slug($data["title"]);
        //set category id
        $data['category_id'] = 0;
        $post_inputs = $this->input->post();
        foreach ($post_inputs as $key => $value) {
            if (strpos($key, 'category_id_') !== false) {
                $data['category_id'] = $value;
            }
        }

        if (empty($data["country_id"])) {
            $data["country_id"] = 0;
        }

        return $this->db->insert('products', $data);
    }

    //edit product details
    public function edit_product_details($id)
    {
        $product = $this->get_product_by_id($id);
        $data = array(
            'price' => $this->input->post('price', true),
            'currency' => $this->input->post('currency', true),
            'discount_rate' => $this->input->post('discount_rate', true),
            'vat_rate' => $this->input->post('vat_rate', true),
            'product_condition' => $this->input->post('product_condition', true),
            'country_id' => $this->input->post('country_id', true),
            'state_id' => $this->input->post('state_id', true),
            'city_id' => $this->input->post('city_id', true),
            'address' => $this->input->post('address', true),
            'zip_code' => $this->input->post('zip_code', true),
            'demo_url' => trim($this->input->post('demo_url', true)),
            'external_link' => trim($this->input->post('external_link', true)),
            'files_included' => trim($this->input->post('files_included', true)),
            'stock' => $this->input->post('stock', true),
            'shipping_time' => $this->input->post('shipping_time', true),
            'shipping_cost_type' => $this->input->post('shipping_cost_type', true),
            'is_free_product' => $this->input->post('is_free_product', true),
            'is_draft' => 0
        );

        $data["price"] = get_price($data["price"], 'database');
        if (empty($data["price"])) {
            $data["price"] = 0;
        }
        if (empty($data["discount_rate"])) {
            $data["discount_rate"] = 0;
        }
        if (empty($data["vat_rate"])) {
            $data["vat_rate"] = 0;
        }
        if (empty($data["product_condition"])) {
            $data["product_condition"] = "";
        }
        if (empty($data["country_id"])) {
            $data["country_id"] = 0;
        }
        if (empty($data["state_id"])) {
            $data["state_id"] = 0;
        }
        if (empty($data["city_id"])) {
            $data["city_id"] = 0;
        }
        if (empty($data["address"])) {
            $data["address"] = "";
        }
        if (empty($data["zip_code"])) {
            $data["zip_code"] = "";
        }
        if (empty($data["external_link"])) {
            $data["external_link"] = "";
        }
        if (empty($data["stock"])) {
            $data["stock"] = 0;
        }
        if (!empty($data["is_free_product"])) {
            $data["is_free_product"] = 1;
        } else {
            $data["is_free_product"] = 0;
        }

        //unset price if bidding system selected
        if ($this->general_settings->bidding_system == 1) {
            $array['price'] = 0;
        }

        if ($this->settings_model->is_shipping_option_require_cost($data["shipping_cost_type"]) == 1) {
            $data["shipping_cost"] = $this->input->post('shipping_cost', true);
            $data["shipping_cost"] = get_price($data["shipping_cost"], 'database');
            $data["shipping_cost_additional"] = $this->input->post('shipping_cost_additional', true);
            $data["shipping_cost_additional"] = get_price($data["shipping_cost_additional"], 'database');
        } else {
            $data["shipping_cost"] = 0;
            $data["shipping_cost_additional"] = 0;
        }

        if ($this->input->post('submit', true) == 'save_as_draft') {
            $data["is_draft"] = 1;
        } else {
            if ($this->general_settings->approve_before_publishing == 0 || $this->auth_user->role == 'admin') {
                $data["status"] = 1;
            }
        }

        $this->db->where('id', clean_number($id));
        return $this->db->update('products', $data);
    }

    //edit product
    public function edit_product($product)
    {
        $data = array(
            'title' => $this->input->post('title', true),
            'product_type' => $this->input->post('product_type', true),
            'listing_type' => $this->input->post('listing_type', true),
            'sku' => $this->input->post('sku', true),
            'description' => $this->input->post('description', false),
        );
        $data["slug"] = str_slug($data["title"]);

        //set category id
        $data['category_id'] = 0;
        $post_inputs = $this->input->post();
        foreach ($post_inputs as $key => $value) {
            if (strpos($key, 'category_id_') !== false) {
                $data['category_id'] = $value;
            }
        }

        if ($product->is_draft != 1) {
            if (is_admin()) {
                $data["visibility"] = $this->input->post('visibility', true);
            }
        }

        $this->db->where('id', $product->id);
        return $this->db->update('products', $data);
    }

    //update custom fields
    public function update_product_custom_fields($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $custom_fields = $this->field_model->generate_custom_fields_array($product->category_id, null);
            if (!empty($custom_fields)) {
                //delete previous custom field values
                $this->field_model->delete_field_product_values_by_product_id($product_id);

                foreach ($custom_fields as $custom_field) {
                    $input_value = $this->input->post('field_' . $custom_field->id, true);
                    //add custom field values
                    if (!empty($input_value)) {
                        if ($custom_field->field_type == 'checkbox') {
                            foreach ($input_value as $key => $value) {
                                $data = array(
                                    'field_id' => $custom_field->id,
                                    'product_id' => $product_id,
                                    'product_filter_key' => $custom_field->product_filter_key
                                );
                                $data['field_value'] = '';
                                $data['selected_option_common_id'] = $value;
                                $this->db->insert('custom_fields_product', $data);
                            }
                        } else {
                            $data = array(
                                'field_id' => $custom_field->id,
                                'product_id' => clean_number($product_id),
                                'product_filter_key' => $custom_field->product_filter_key,
                            );
                            if ($custom_field->field_type == 'radio_button' || $custom_field->field_type == 'dropdown') {
                                $data['field_value'] = '';
                                $data['selected_option_common_id'] = $input_value;
                            } else {
                                $data['field_value'] = $input_value;
                                $data['selected_option_common_id'] = '';
                            }
                            $this->db->insert('custom_fields_product', $data);
                        }
                    }
                }
            }
        }
    }

    //update slug
    public function update_slug($id)
    {
        $product = $this->get_product_by_id($id);
        if (!empty($product)) {
            if (empty($product->slug) || $product->slug == "-") {
                $data = array(
                    'slug' => $product->id,
                );
            } else {
                if ($this->general_settings->product_link_structure == "id-slug") {
                    $data = array(
                        'slug' => $product->id . "-" . $product->slug,
                    );
                } else {
                    $data = array(
                        'slug' => $product->slug . "-" . $product->id,
                    );
                }
            }
            if (!empty($this->page_model->check_page_slug_for_product($data["slug"]))) {
                $data["slug"] .= uniqid();
            }
            $this->db->where('id', $product->id);
            return $this->db->update('products', $data);
        }
    }

    //build sql query string
    public function query_string($type = "active", $return_count = false, $compile_query = true)
    {
        $select = "";
        if ($return_count == true) {
            $select = "COUNT(products.id) AS count";
        } else {
            $select = "products.id, products.title, products.slug, products.product_type, products.listing_type, products.category_id,  products.price, products.currency, products.discount_rate, 
            products.user_id, products.is_promoted, products.rating, products.hit, products.is_free_product, products.promote_end_date, products.description, products.product_condition, products.created_at, 
            users.username AS user_username, users.shop_name AS shop_name, users.role AS user_role, users.slug AS user_slug,
            (SELECT CONCAT(storage, '::', image_small) FROM images WHERE products.id = images.product_id ORDER BY is_main DESC LIMIT 1) AS image,
            (SELECT CONCAT(storage, '::', image_small) FROM images WHERE products.id = images.product_id ORDER BY is_main DESC LIMIT 1, 1) AS image_second,
            (SELECT COUNT(wishlist.id) FROM wishlist WHERE products.id = wishlist.product_id) AS wishlist_count";
            if ($this->auth_check) {
                $select .= ", (SELECT COUNT(wishlist.id) FROM wishlist WHERE products.id = wishlist.product_id AND wishlist.user_id = " . clean_number($this->auth_user->id) . ") AS is_in_wishlist";
            } else {
                $select .= ", 0 AS is_in_wishlist";
            }
        }

        $status = ($type == 'draft' || $type == 'pending') ? 0 : 1;
        $visibility = ($type == 'hidden') ? 0 : 1;
        $is_draft = ($type == 'draft') ? 1 : 0;

        $this->db->select($select);
        $this->db->from('products');
        $this->db->join('users', 'products.user_id = users.id');
        if ($type == 'wishlist') {
            $this->db->join('wishlist', 'products.id = wishlist.product_id');
        }
        $this->db->where('users.banned', 0);
        $this->db->where('products.status', $status);
        $this->db->where('products.visibility', $visibility);
        $this->db->where('products.is_draft', $is_draft);
        $this->db->where('products.is_deleted', 0);
        if ($type == 'promoted') {
            $this->db->where('products.is_promoted', 1);
        }
        if ($this->general_settings->vendor_verification_system == 1) {
            $this->db->where('users.role !=', 'member');
        }
        //default location
        if ($this->default_location_id != 0) {
            $this->db->where('products.country_id', $this->default_location_id);
        }

        if ($compile_query == true) {
            return $this->db->get_compiled_select() . " ";
        }
    }

    //filter products
    public function sql_filter_products($category_id, $return_count = false)
    {
        $category_id = clean_number($category_id);
        $country = clean_number($this->input->get("country", true));
        $state = clean_number($this->input->get("state", true));
        $city = clean_number($this->input->get("city", true));
        $condition = remove_special_characters($this->input->get("condition", true));
        $p_min = remove_special_characters($this->input->get("p_min", true));
        $p_max = remove_special_characters($this->input->get("p_max", true));
        $sort = remove_special_characters($this->input->get("sort", true));
        $search = remove_special_characters(trim($this->input->get('search', true)));

        //check if custom filters selected
        $custom_filters = array();
        $session_custom_filters = get_sess_product_filters();
        $query_string_filters = get_filters_query_string_array();
        $array_queries = array();
        if (!empty($session_custom_filters)) {
            foreach ($session_custom_filters as $filter) {
                if (isset($query_string_filters[$filter->product_filter_key])) {
                    $item = new stdClass();
                    $item->product_filter_key = $filter->product_filter_key;
                    $item->product_filter_value = @$query_string_filters[$filter->product_filter_key];
                    array_push($custom_filters, $item);
                }
            }
        }

        if (!empty($custom_filters)) {
            foreach ($custom_filters as $filter) {
                if (!empty($filter)) {
                    $filter->product_filter_key = remove_special_characters($filter->product_filter_key);
                    $filter->product_filter_value = remove_special_characters($filter->product_filter_value);
                    $this->db->join('custom_fields_options', 'custom_fields_options.common_id = custom_fields_product.selected_option_common_id');
                    $this->db->select('product_id');
                    $this->db->where('custom_fields_product.product_filter_key', $filter->product_filter_key);
                    $this->db->where('custom_fields_options.field_option', $filter->product_filter_value);
                    $this->db->from('custom_fields_product');
                    $array_queries[] = $this->db->get_compiled_select();
                    $this->db->reset_query();
                }
            }
            $this->query_string("active", $return_count, false);
            foreach ($array_queries as $query) {
                $this->db->where("products.id IN ($query)", NULL, FALSE);
            }
        } else {
            $this->query_string("active", $return_count, false);
        }

        //add protuct filter options
        if (!empty($category_id)) {
            $category_tree_ids = $this->category_model->get_category_tree_ids_string($category_id);
            if (!empty($category_tree_ids)) {
                $this->db->where("products.category_id IN (" . $category_tree_ids . ")", NULL, FALSE);
                $this->db->order_by('products.is_promoted', 'DESC');
            }
        }
        if (!empty($country)) {
            $this->db->where('products.country_id', $country);
        }
        if (!empty($state)) {
            $this->db->where('products.state_id', $state);
        }
        if (!empty($city)) {
            $this->db->where('products.city_id', $city);
        }
        if (!empty($condition)) {
            $this->db->where('products.product_condition', $condition);
        }
        if ($p_min != "") {
            $this->db->where('products.price >=', intval($p_min * 100));
        }
        if ($p_max != "") {
            $this->db->where('products.price <=', intval($p_max * 100));
        }
        if ($search != "") {
            $this->db->group_start();
            $this->db->like('products.title', $search);
            $this->db->or_like('products.description', $search);
            $this->db->group_end();
            $this->db->order_by('products.is_promoted', 'DESC');
        }
        //sort products
        if (!empty($sort) && $sort == "lowest_price") {
            $this->db->order_by('products.price');
        } elseif (!empty($sort) && $sort == "highest_price") {
            $this->db->order_by('products.price', 'DESC');
        } else {
            $this->db->order_by('products.created_at', 'DESC');
        }

        return $this->db->get_compiled_select();
    }

    //search products (AJAX search)
    public function search_products($search)
    {
        $like = '%' . remove_forbidden_characters($search) . '%';
        $sql = $this->query_string() . "AND products.title LIKE ? ORDER BY products.is_promoted DESC LIMIT 8";
        $query = $this->db->query($sql, array($like));
        return $query->result();
    }

    //get products
    public function get_products()
    {
        $sql = $this->query_string() . "ORDER BY products.created_at";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get limited products
    public function get_products_limited($limit)
    {
        $sql = $this->query_string() . "ORDER BY products.created_at DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($limit)));
        return $query->result();
    }

    //get promoted products
    public function get_promoted_products()
    {
        $sql = $this->query_string("promoted") . " ORDER BY products.created_at DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get promoted products
    public function get_promoted_products_limited($offset, $per_page)
    {
        $sql = $this->query_string("promoted") . " ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get promoted products count
    public function get_promoted_products_count()
    {
        $sql = $this->query_string("promoted", true) . " AND products.is_promoted = 1";
        $query = $this->db->query($sql);
        return $query->row()->count;
    }

    //check promoted products
    public function check_promoted_products()
    {
        $query = $this->db->query("SELECT * FROM products WHERE products.is_promoted = 1");
        $products = $query->result();
        if (!empty($products)) {
            foreach ($products as $item) {
                if (date_difference($item->promote_end_date, date('Y-m-d H:i:s')) < 1) {
                    $data = array(
                        'is_promoted' => 0,
                    );
                    $this->db->where('id', $item->id);
                    $this->db->update('products', $data);
                }
            }
        }
    }

    //get paginated filtered products
    public function get_paginated_filtered_products($category_id, $offset, $per_page)
    {
        $sql = $this->sql_filter_products($category_id, false) . " " . "LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get paginated filtered products count
    public function get_paginated_filtered_products_count($category_id)
    {
        $sql = $this->sql_filter_products($category_id, true);
        $query = $this->db->query($sql);
        return $query->row()->count;
    }

    //get products count by category
    public function get_products_count_by_category($category_id)
    {
        $sql = "SELECT COUNT(id) AS count FROM products WHERE products.category_id = ?";
        $query = $this->db->query($sql, array(clean_number($category_id)));
        return $query->row()->count;
    }

    //get related products
    public function get_related_products($product)
    {
        $sql = $this->query_string() . "AND products.category_id = ? AND products.id != ? ORDER BY RAND() DESC LIMIT 8";
        $query = $this->db->query($sql, array(clean_number($product->category_id), clean_number($product->id)));
        $rows = $query->result_array();

        $rows_2 = array();
        $category = $this->category_model->get_category($product->category_id);
        if (empty($category)) {
            return $rows;
        }
        if ($category->parent_id != 0) {
            $category = $this->category_model->get_category($category->parent_id);
        }
        if (empty($category)) {
            return $rows;
        }
        $category_ids_array = $this->category_model->get_category_tree_ids_array($category->parent_id);
        if (!empty($category_ids_array)) {
            $sql = $this->query_string() . "AND products.category_id != ? AND products.id != ? AND products.category_id IN ? ORDER BY RAND() DESC LIMIT 8";
            $query = $this->db->query($sql, array(clean_number($product->category_id), clean_number($product->id), $category_ids_array));
            $rows = $query->result_array();
        }
        if (!empty($rows_2)) {
            return array_merge($rows, $rows_2);
        }
        return $rows;
    }

    //get user other products
    public function get_user_other_products($user_id, $limit, $product_id)
    {
        $sql = $this->query_string() . "AND users.id = ? AND products.id != ?  ORDER BY products.created_at DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($product_id), clean_number($limit)));
        return $query->result();
    }

    //get paginated user products
    public function get_paginated_user_products($user_id, $offset, $per_page)
    {
        $sql = $this->query_string() . "AND users.id = ? ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get user products count
    public function get_user_products_count($user_id)
    {
        $sql = $this->query_string("active", true) . "AND users.id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }

    //get user products
    public function get_user_products($user_id, $product_id)
    {
        $sql = $this->query_string() . "AND users.id = ? AND products.id != ? ORDER BY products.created_at DESC";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($product_id)));
        return $query->result();
    }

    //get paginated user pending products
    public function get_paginated_user_pending_products($user_id, $offset, $per_page)
    {
        $sql = $this->query_string('pending') . "AND users.id = ? ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get user pending products count
    public function get_user_pending_products_count($user_id)
    {
        $sql = $this->query_string('pending', true) . "AND users.id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }

    //get paginated drafts
    public function get_paginated_user_drafts($user_id, $offset, $per_page)
    {
        $sql = $this->query_string('draft') . "AND users.id = ? ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get user drafts count
    public function get_user_drafts_count($user_id)
    {
        $sql = $this->query_string('draft', true) . " AND users.id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }

    //get paginated user hidden products
    public function get_paginated_user_hidden_products($user_id, $offset, $per_page)
    {
        $sql = $this->query_string('hidden') . "AND users.id = ? ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get user hidden products count
    public function get_user_hidden_products_count($user_id)
    {
        $sql = $this->query_string('hidden', true) . "AND users.id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }


    //get user wishlist products
    public function get_paginated_user_wishlist_products($user_id, $offset, $per_page)
    {
        $sql = $this->query_string('wishlist') . "AND wishlist.user_id = ? ORDER BY products.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get user wishlist products count
    public function get_user_wishlist_products_count($user_id)
    {
        $sql = $this->query_string('wishlist', true) . " AND wishlist.user_id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }

    //get guest wishlist products
    public function get_paginated_guest_wishlist_products($offset, $per_page)
    {
        $wishlist = $this->session->userdata('mds_guest_wishlist');
        if (!empty($wishlist) && item_count($wishlist) > 0) {
            $sql = $this->query_string() . "AND products.id IN ? ORDER BY products.created_at DESC LIMIT ?,?";
            $query = $this->db->query($sql, array($wishlist, clean_number($offset), clean_number($per_page)));
            return $query->result();
        }
        return array();
    }

    //get guest wishlist products count
    public function get_guest_wishlist_products_count()
    {
        $wishlist = $this->session->userdata('mds_guest_wishlist');
        if (!empty($wishlist) && item_count($wishlist) > 0) {
            $sql = $this->query_string('active', true) . "AND products.id IN ?";
            $query = $this->db->query($sql, array($wishlist));
            return $query->row()->count;
        }
        return 0;
    }

    //get user downloads count
    public function get_user_downloads_count($user_id)
    {
        $sql = "SELECT COUNT(id) AS count FROM digital_sales WHERE buyer_id = ?";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->row()->count;
    }

    //get paginated downloads
    public function get_paginated_user_downloads($user_id, $offset, $per_page)
    {
        $sql = "SELECT * FROM digital_sales WHERE buyer_id = ? ORDER BY purchase_date DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($user_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get digital sale
    public function get_digital_sale($sale_id)
    {
        $sql = "SELECT * FROM digital_sales WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($sale_id)));
        return $query->row();
    }

    //get digital sale by buyer id
    public function get_digital_sale_by_buyer_id($buyer_id, $product_id)
    {
        $sql = "SELECT * FROM digital_sales WHERE buyer_id = ? AND product_id = ?";
        $query = $this->db->query($sql, array(clean_number($buyer_id), clean_number($product_id)));
        return $query->row();
    }

    //get digital sale by order id
    public function get_digital_sale_by_order_id($buyer_id, $product_id, $order_id)
    {
        $sql = "SELECT * FROM digital_sales WHERE buyer_id = ? AND product_id = ? AND order_id = ?";
        $query = $this->db->query($sql, array(clean_number($buyer_id), clean_number($product_id), clean_number($order_id)));
        return $query->row();
    }

    //get product by id
    public function get_product_by_id($id)
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get available product
    public function get_available_product($id)
    {
        $sql = "SELECT products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug FROM products 
                INNER JOIN users ON products.user_id = users.id AND users.banned = 0
                WHERE products.status = 1 AND products.visibility = 1 AND products.is_draft = 0 AND products.stock > 0 AND products.is_deleted = 0 AND products.id = ?";
        if ($this->general_settings->vendor_verification_system == 1) {
            $sql .= " AND users.role != 'member'";
        }
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get product by slug
    public function get_product_by_slug($slug)
    {
        $sql = "SELECT products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug FROM products 
                INNER JOIN users ON products.user_id = users.id AND users.banned = 0
                WHERE products.is_draft = 0 AND products.is_deleted = 0 AND products.slug = ?";
        if ($this->general_settings->vendor_verification_system == 1) {
            $sql .= " AND users.role != 'member'";
        }
        $query = $this->db->query($sql, array(clean_str($slug)));
        return $query->row();
    }

    //is product in wishlist
    public function is_product_in_wishlist($product_id)
    {
        if ($this->auth_check) {
            $sql = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($product_id)));
            if (!empty($query->row())) {
                return true;
            }
        } else {
            $wishlist = $this->session->userdata('mds_guest_wishlist');
            if (!empty($wishlist)) {
                if (in_array($product_id, $wishlist)) {
                    return true;
                }
            }
        }
        return false;
    }

    //get product wishlist count
    public function get_product_wishlist_count($product_id)
    {
        $sql = "SELECT COUNT(id) AS count FROM wishlist WHERE product_id = ?";
        $query = $this->db->query($sql, array(clean_number($product_id)));
        return $query->row()->count;
    }

    //add remove wishlist
    public function add_remove_wishlist($product_id)
    {
        if ($this->auth_check) {
            if ($this->is_product_in_wishlist($product_id)) {
                $this->db->where('user_id', $this->auth_user->id);
                $this->db->where('product_id', clean_number($product_id));
                $this->db->delete('wishlist');
            } else {
                $data = array(
                    'user_id' => $this->auth_user->id,
                    'product_id' => clean_number($product_id)
                );
                $this->db->insert('wishlist', $data);
            }
        } else {
            if ($this->is_product_in_wishlist($product_id)) {
                $wishlist = array();
                if (!empty($this->session->userdata('mds_guest_wishlist'))) {
                    $wishlist = $this->session->userdata('mds_guest_wishlist');
                }
                $new = array();
                if (!empty($wishlist)) {
                    foreach ($wishlist as $item) {
                        if ($item != clean_number($product_id)) {
                            array_push($new, $item);
                        }
                    }
                }
                $this->session->set_userdata('mds_guest_wishlist', $new);
            } else {
                $wishlist = array();
                if (!empty($this->session->userdata('mds_guest_wishlist'))) {
                    $wishlist = $this->session->userdata('mds_guest_wishlist');
                }
                array_push($wishlist, clean_number($product_id));
                $this->session->set_userdata('mds_guest_wishlist', $wishlist);
            }
        }
    }

    //increase product hit
    public function increase_product_hit($product)
    {
        if (!empty($product)):
            if (!isset($_COOKIE['modesy_product_' . $product->id])) :
                //increase hit
                setcookie("modesy_product_" . $product->id, '1', time() + (86400 * 300), "/");
                $data = array(
                    'hit' => $product->hit + 1
                );

                $this->db->where('id', $product->id);
                $this->db->update('products', $data);

            endif;
        endif;
    }

    //get rss products by category
    public function get_rss_products_by_category($category_id)
    {
        $category_ids_array = $this->category_model->get_category_tree_ids_array($category_id);
        if (empty($category_ids_array)) {
            return array();
        }

        $sql = "SELECT products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug,
                (SELECT CONCAT(storage, '::', image_small) FROM images WHERE products.id = images.product_id ORDER BY is_main DESC LIMIT 1) AS image
                FROM products 
                INNER JOIN users ON products.user_id = users.id AND users.banned = 0
                WHERE products.status = 1 AND products.visibility = 1 AND products.is_draft = 0 AND products.is_deleted = 0 AND products.category_id IN ?";
        if ($this->general_settings->vendor_verification_system == 1) {
            $sql .= " AND users.role != 'member'";
        }
        $sql .= " ORDER BY products.created_at DESC";
        $query = $this->db->query($sql, array($category_ids_array));
        return $query->result();
    }

    //get rss products by user
    public function get_rss_products_by_user($user_id)
    {
        $sql = "SELECT products.*, users.username as user_username, users.shop_name as shop_name, users.role as user_role, users.slug as user_slug,
                (SELECT CONCAT(storage, '::', image_small) FROM images WHERE products.id = images.product_id ORDER BY is_main DESC LIMIT 1) AS image
                FROM products 
                INNER JOIN users ON products.user_id = users.id AND users.banned = 0
                WHERE products.status = 1 AND products.visibility = 1 AND products.is_draft = 0 AND products.is_deleted = 0 AND users.id = ?";
        if ($this->general_settings->vendor_verification_system == 1) {
            $sql .= " AND users.role != 'member'";
        }
        $sql .= " ORDER BY products.created_at DESC";
        $query = $this->db->query($sql, array(clean_number($user_id)));
        return $query->result();
    }

    //delete product
    public function delete_product($product_id)
    {
        $product = $this->get_product_by_id($product_id);
        if (!empty($product)) {
            $data = array(
                'is_deleted' => 1
            );
            $this->db->where('id', $product->id);
            return $this->db->update('products', $data);
        }
        return false;
    }

    /*
    *------------------------------------------------------------------------------------------
    * LICENSE KEYS
    *------------------------------------------------------------------------------------------
    */

    //add license keys
    public function add_license_keys($product_id)
    {
        $license_keys = trim($this->input->post('license_keys', true));
        $allow_duplicate = $this->input->post('allow_duplicate', true);

        $license_keys_array = explode(",", $license_keys);
        if (!empty($license_keys_array)) {
            foreach ($license_keys_array as $license_key) {
                $license_key = trim($license_key);
                if (!empty($license_key)) {

                    //check duplicate
                    $add_key = true;
                    if (empty($allow_duplicate)) {
                        $row = $this->check_license_key($product_id, $license_key);
                        if (!empty($row)) {
                            $add_key = false;
                        }
                    }

                    //add license key
                    if ($add_key == true) {
                        $data = array(
                            'product_id' => $product_id,
                            'license_key' => trim($license_key),
                            'is_used' => 0
                        );
                        $this->db->insert('product_license_keys', $data);
                    }

                }
            }
        }
    }

    //get license keys
    public function get_license_keys($product_id)
    {
        $sql = "SELECT * FROM product_license_keys WHERE product_id = ?";
        $query = $this->db->query($sql, array(clean_number($product_id)));
        return $query->result();
    }

    //get license key
    public function get_license_key($id)
    {
        $sql = "SELECT * FROM product_license_keys WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get unused license key
    public function get_unused_license_key($product_id)
    {
        $sql = "SELECT * FROM product_license_keys WHERE product_id = ? AND is_used = 0 LIMIT 1";
        $query = $this->db->query($sql, array(clean_number($product_id)));
        return $query->row();
    }

    //check license key
    public function check_license_key($product_id, $license_key)
    {
        $sql = "SELECT * FROM product_license_keys WHERE product_id = ? AND license_key = ?";
        $query = $this->db->query($sql, array(clean_number($product_id), $license_key));
        return $query->row();
    }

    //set license key used
    public function set_license_key_used($id)
    {
        $data = array(
            'is_used' => 1
        );
        $this->db->where('id', clean_number($id));
        $this->db->update('product_license_keys', $data);
    }

    //delete license key
    public function delete_license_key($id)
    {
        $license_key = $this->get_license_key($id);
        if (!empty($license_key)) {
            $this->db->where('id', $license_key->id);
            return $this->db->delete('product_license_keys');
        }
        return false;
    }

}
