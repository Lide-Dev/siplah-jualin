<?php defined('BASEPATH') or exit('No direct script access allowed');

//get parent categories
if (!function_exists('get_parent_categories')) {
    function get_parent_categories($categories)
    {
        if (!empty($categories)) {
            return array_filter($categories, function ($item) {
                return $item->parent_id == 0;
            });
        }
        return null;
    }
}

//get subcategories
if (!function_exists('get_subcategories')) {
    function get_subcategories($categories, $parent_id)
    {
        if (!empty($categories)) {
            return array_filter($categories, function ($item) use ($parent_id) {
                return $item->parent_id == $parent_id;
            });
        }
        return null;
    }
}

//get navigation categories
if (!function_exists('get_navigation_categories')) {
    function get_navigation_categories($categories)
    {
        if (!empty($categories)) {
            return array_filter($categories, function ($item) {
                return $item->show_image_on_navigation == 1;
            });
        }
        return null;
    }
}

//get category
if (!function_exists('get_category')) {
    function get_category($categories, $id)
    {
        if (!empty($categories)) {
            return array_filter($categories, function ($item) use ($id) {
                return $item->id == $id;
            });
        }
        return null;
    }
}

if (!function_exists('get_category_by_id')) {
    function get_category_by_id($id)
    {
        $ci =& get_instance();
        return $ci->category_model->get_category($id);
    }
}

//get category
if (!function_exists('get_category_by_id')) {
    function get_category_by_id($id)
    {
        $ci =& get_instance();
        return $ci->category_model->get_category($id);
    }
}

//get category name
if (!function_exists('category_name')) {
    function category_name($category)
    {
        if (!empty($category)) {
            if (!empty($category->name)) {
                return html_escape($category->name);
            } else {
                if (!empty($category->second_name)) {
                    return html_escape($category->second_name);
                }
            }
        }
        return "";
    }
}

//get category image url
if (!function_exists('get_category_image_url')) {
    function get_category_image_url($category)
    {
        if ($category->storage == "aws_s3") {
            $ci =& get_instance();
            return $ci->aws_base_url . $category->image;
        } else {
            return base_url() . $category->image;
        }
    }
}

//generate ids string
if (!function_exists('generate_ids_string')) {
    function generate_ids_string($array)
    {
        if (empty($array)) {
            return "0";
        } else {
            $array_new = array();
            foreach ($array as $item) {
                if (!empty(clean_number($item))) {
                    array_push($array_new, clean_number($item));
                }
            }
            return implode(',', $array_new);
        }
    }
}


//product form data
if (!function_exists('get_product_form_data')) {
    function get_product_form_data($product)
    {
        $ci =& get_instance();
        $data = new stdClass();
        $data->add_to_cart_url = "";
        $data->button = "";

        if (!empty($product)) {
            $disabled = "";
            if ($product->stock < 1) {
                $disabled = " disabled";
            }
            if ($product->listing_type == 'sell_on_site') {
                if ($product->is_free_product != 1) {
                    $data->add_to_cart_url = base_url() . 'add-to-cart';
                    $data->button = '<button class="btn btn-md btn-block btn-product-cart"' . $disabled . '><i class="icon-cart-solid"></i>' . trans("add_to_cart") . '</button>';
                }
            } elseif ($product->listing_type == 'bidding') {
                $data->add_to_cart_url = base_url() . 'request-quote';
                $data->button = '<button class="btn btn-md btn-block btn-product-cart"' . $disabled . '>' . trans("request_a_quote") . '</button>';
                if (!$ci->auth_check && $product->listing_type == 'bidding') {
                    $data->button = '<button type="button" data-toggle="modal" data-target="#loginModal" class="btn btn-md btn-block btn-product-cart"' . $disabled . '>' . trans("request_a_quote") . '</button>';
                }
            } else {
                if (!empty($product->external_link)) {
                    $data->button = '<a href="' . $product->external_link . '" class="btn btn-md btn-block" target="_blank">' . trans("buy_now") . '</a>';
                }
            }
        }
        return $data;
    }
}

//get product item image
if (!function_exists('get_product_item_image')) {
    function get_product_item_image($product, $get_second = false)
    {
        $ci =& get_instance();
        if (!empty($product)) {
            $image = $product->image;
            if (!empty($product->image_second) && $get_second == true) {
                $image = $product->image_second;
            }
            if (!empty($image)) {
                $image_array = explode("::", $image);
                if (!empty($image_array[0]) && !empty($image_array[1])) {
                    if ($image_array[0] == "aws_s3") {
                        return $ci->aws_base_url . "uploads/images/" . $image_array[1];
                    } else {
                        return base_url() . "uploads/images/" . $image_array[1];
                    }
                }
            }
        }
        return base_url() . 'assets/img/no-image.jpg';
    }
}

//get latest products
if (!function_exists('get_latest_products')) {
    function get_latest_products($limit)
    {
        $ci =& get_instance();
        $key = "latest_products";
        if ($ci->default_location_id != 0) {
            $key = "latest_products_location_" . $ci->default_location_id;
        }
        $latest_products = get_cached_data($key);
        if (empty($latest_products)) {
            $latest_products = $ci->product_model->get_products_limited($limit);
            set_cache_data($key, $latest_products);
        }
        return $latest_products;
    }
}

//get promoted products
if (!function_exists('get_promoted_products')) {
    function get_promoted_products($offset, $per_page)
    {
        $ci =& get_instance();
        $key = "promoted_products_" . $offset . "_" . $per_page;
        if ($ci->default_location_id != 0) {
            $key = "promoted_products_location_" . $ci->default_location_id . "_" . $offset . "_" . $per_page;
        }
        $promoted_products = get_cached_data($key);
        if (empty($promoted_products)) {
            $promoted_products = $ci->product_model->get_promoted_products_limited($offset, $per_page);
            set_cache_data($key, $promoted_products);
        }
        return $promoted_products;
    }
}

//get promoted products count
if (!function_exists('get_promoted_products_count')) {
    function get_promoted_products_count()
    {
        $ci =& get_instance();
        $key = "promoted_products_count";
        if ($ci->default_location_id != 0) {
            $key = "promoted_products_count_location_" . $ci->default_location_id;
        }
        $count = get_cached_data($key);
        if (empty($count)) {
            $count = $ci->product_model->get_promoted_products_count();
            set_cache_data($key, $count);
        }
        return $count;
    }
}

//is product in wishlist
if (!function_exists('is_product_in_wishlist')) {
    function is_product_in_wishlist($product)
    {
        $ci =& get_instance();
        if ($ci->auth_check) {
            if (!empty($product->is_in_wishlist)) {
                return true;
            }
        } else {
            $wishlist = $ci->session->userdata('mds_guest_wishlist');
            if (!empty($wishlist)) {
                if (in_array($product->id, $wishlist)) {
                    return true;
                }
            }
        }
        return false;
    }
}

//get currency
if (!function_exists('get_currency')) {
    function get_currency($currency_key)
    {
        $ci =& get_instance();
        if (!empty($ci->currencies)) {
            if (isset($ci->currencies[$currency_key])) {
                return $ci->currencies[$currency_key]["hex"];
            }
        }
        return "";
    }
}

//calculate product price
if (!function_exists('calculate_product_price')) {
    function calculate_product_price($price, $discount_rate)
    {
        if (!empty($price)) {
            if (!empty($discount_rate)) {
                $price = $price - (($price * $discount_rate) / 100);
            }
            return $price;
        }
        return 0;
    }
}

//calculate vat
if (!function_exists('calculate_vat')) {
    function calculate_vat($price_calculated, $vat_rate)
    {
        return ($price_calculated * $vat_rate) / 100;
    }
}

//calculate product vat
if (!function_exists('calculate_product_vat')) {
    function calculate_product_vat($product)
    {
        if (!empty($product)) {
            if (!empty($product->vat_rate)) {
                $price = calculate_product_price($product->price, $product->discount_rate);
                return ($price * $product->vat_rate) / 100;
            }
        }
        return 0;
    }
}

//calculate earned amount
if (!function_exists('calculate_earned_amount')) {
    function calculate_earned_amount($product)
    {
        $ci =& get_instance();
        if (!empty($product)) {
            $price = calculate_product_price($product->price, $product->discount_rate) + calculate_product_vat($product);
            return $price - (($price * $ci->general_settings->commission_rate) / 100);
        }
        return 0;
    }
}

//price formatted
if (!function_exists('price_formatted')) {
    function price_formatted($price, $currency, $format = null)
    {
        $ci =& get_instance();
        $price = $price / 100;
        $dec_point = '.';
        $thousands_sep = ',';
        if ($ci->thousands_separator != '.') {
            $dec_point = ',';
            $thousands_sep = '.';
        }

        if (is_int($price)) {
            $price = number_format($price, 0, $dec_point, $thousands_sep);
        } else {
            $price = number_format($price, 2, $dec_point, $thousands_sep);
        }
        $price = price_currency_format($price, $currency);
        return $price;
    }
}

//price currency format
if (!function_exists('price_currency_format')) {
    function price_currency_format($price, $currency)
    {
        $ci =& get_instance();
        $currency = get_currency($currency);
        $space = "";
        if ($ci->payment_settings->space_between_money_currency == 1) {
            $space = " ";
        }
        if ($ci->payment_settings->currency_symbol_format == "left") {
            $price = "<span>" . $currency . "</span>" . $space . $price;
        } else {
            $price = $price . $space . "<span>" . $currency . "</span>";
        }
        return $price;
    }
}

//get price
if (!function_exists('get_price')) {
    function get_price($price, $format_type)
    {
        $ci =& get_instance();

        if ($format_type == "input") {
            $price = $price / 100;
            if (is_int($price)) {
                $price = number_format($price, 0, ".", "");
            } else {
                $price = number_format($price, 2, ".", "");
            }
            if ($ci->thousands_separator == ',') {
                $price = str_replace('.', ',', $price);
            }
            return $price;
        } elseif ($format_type == "decimal") {
            $price = $price / 100;
            return number_format($price, 2, ".", "");
        } elseif ($format_type == "database") {
            $price = str_replace(',', '.', $price);
            $price = floatval($price);
            $price = number_format($price, 2, '.', '') * 100;
            return $price;
        } elseif ($format_type == "separator_format") {
            $price = $price / 100;
            $dec_point = '.';
            $thousands_sep = ',';
            if ($ci->thousands_separator != '.') {
                $dec_point = ',';
                $thousands_sep = '.';
            }
            return number_format($price, 2, $dec_point, $thousands_sep);
        }
    }
}

//get variation label
if (!function_exists('get_variation_label')) {
    function get_variation_label($label_array, $lang_id)
    {
        $ci =& get_instance();
        $label = "";
        if (!empty($label_array)) {
            $label_array = unserialize($label_array);
            foreach ($label_array as $item) {
                if ($lang_id == $item['lang_id']) {
                    $label = $item['label'];
                    break;
                }
            }
            if (empty($label)) {
                foreach ($label_array as $item) {
                    if ($ci->general_settings->site_lang == $item['lang_id']) {
                        $label = $item['label'];
                        break;
                    }
                }
            }
        }
        return $label;
    }
}

//get variation option name
if (!function_exists('get_variation_option_name')) {
    function get_variation_option_name($names_array, $lang_id)
    {
        $ci =& get_instance();
        $name = "";
        if (!empty($names_array)) {
            $names_array = unserialize($names_array);
            foreach ($names_array as $item) {
                if ($lang_id == $item['lang_id']) {
                    $name = $item['option_name'];
                    break;
                }
            }
            if (empty($name)) {
                foreach ($names_array as $item) {
                    if ($ci->general_settings->site_lang == $item['lang_id']) {
                        $name = $item['option_name'];
                        break;
                    }
                }
            }
        }
        return $name;
    }
}

//get variation default option
if (!function_exists('get_variation_default_option')) {
    function get_variation_default_option($variation_id)
    {
        $ci =& get_instance();
        return $ci->variation_model->get_variation_default_option($variation_id);
    }
}

//get variation sub options
if (!function_exists('get_variation_sub_options')) {
    function get_variation_sub_options($parent_id)
    {
        $ci =& get_instance();
        return $ci->variation_model->get_variation_sub_options($parent_id);
    }
}

//is there variation uses different price
if (!function_exists('is_there_variation_uses_different_price')) {
    function is_there_variation_uses_different_price($product_id, $except_id = null)
    {
        $ci =& get_instance();
        return $ci->variation_model->is_there_variation_uses_different_price($product_id, $except_id);
    }
}

//discount rate format
if (!function_exists('discount_rate_format')) {
    function discount_rate_format($discount_rate)
    {
        return $discount_rate . "%";
    }
}

?>