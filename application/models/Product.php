<?php

class Product
{
    public $id;
    public $title;
    public $slug;
    // public $listing_type;
    public $sku;
    public $kbki;
    public $category_id;
    public $custom_category;
    public $price;
    public $is_price_zone;
    public $currency;
    public $discount_rate;
    public $vat_rate;
    public $description;
    public $product_condition;
    // public $city_id;
    // public $address;
    public $width;
    public $length;
    public $height;
    public $weight;
    public $publisher;
    public $warranty;
    public $is_homemade;
    public $is_umkm_product;
    public $is_kemendikbud_product;
    public $type_product_id;
    public $status_product_id;
    public $availability_status;
    public $novelty;
    public $guarantee;
    public $shipping_courier_id;

    public $catalog_id;
    public $catalog_type;
    public $user_id;
    public $admin_id;
    public $status;
    // public $is_promoted;
    // public $is_promoted;
    public $visibility;
    public $rating;
    public $hit;
    public $stock;
    public $minimum_order;
    public $shipping_method;
    public $shipping_time;
    public $is_deleted;
    public $is_draft;
    public $is_free_product;
    public $created_at;

    public function __construct()
    {
        if ($this->is_price_zone == 1) {
            $this->price = json_decode($this->price);
        }
    }

    public function category_name()
    {
        $ci = get_instance();
        return $ci->category_model->get_category($this->category_id)->description;
    }

    public function type_product_name()
    {
        $ci = get_instance();
        return $ci->product_model->get_type_product($this->type_product_id)->name;
    }

    public function status_product_name()
    {
        $ci = get_instance();
        return $ci->product_model->get_status_product($this->status_product_id)->name;
    }

    public function user_name()
    {
        $ci = get_instance();
        return $ci->auth_model->get_user($this->status_product_id)->username;
    }

    public function shop_name()
    {
        $ci = get_instance();
        // dd($ci->auth_model->get_shop($this->user_id));
        return $ci->auth_model->get_shop($this->user_id)->supplier_name;
    }

    public function courier_name()
    {
        // dd()
        $ci = get_instance();
        return $ci->product_model->get_couriers($this->shipping_courier_id)->name;
    }

    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        } else if (method_exists($this, $name)) {
            $this->$name();
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
