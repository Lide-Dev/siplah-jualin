<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Custom Helpers
 *
 */

if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
    $ci =& get_instance();
    $ci->load->helper('url');
    redirect(current_url());
    exit();
}

//post method
if (!function_exists('post_method')) {
    function post_method()
    {
        $ci =& get_instance();
        if ($ci->input->method(FALSE) != 'post') {
            exit();
        }
    }
}

//get method
if (!function_exists('get_method')) {
    function get_method()
    {
        $ci =& get_instance();
        if ($ci->input->method(FALSE) != 'get') {
            exit();
        }
    }
}

//check auth
if (!function_exists('lang_base_url')) {
    function lang_base_url()
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->lang_base_url;
    }
}

//check auth
if (!function_exists('auth_check')) {
    function auth_check()
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->auth_model->is_logged_in();
    }
}

//is admin
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $ci =& get_instance();
        if ($ci->auth_check) {
            if ($ci->auth_user->role == "admin") {
                return true;
            }
        }
        return false;
    }
}

//get logged user
if (!function_exists('user')) {
    function user()
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        $user = $ci->auth_model->get_logged_user();
        if (empty($user)) {
            $ci->auth_model->logout();
        } else {
            return $user;
        }
    }
}

//get user by id
if (!function_exists('get_user')) {
    function get_user($user_id)
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->auth_model->get_user($user_id);
    }
}

//get shop name
if (!function_exists('get_shop_name')) {
    function get_shop_name($user)
    {
        if (!empty($user)) {
            if (!empty($user->shop_name) && ($user->role == 'admin' || $user->role == 'vendor')) {
                return html_escape($user->shop_name);
            } else {
                return html_escape($user->username);
            }
        }
    }
}

//get shop name product
if (!function_exists('get_shop_name_product')) {
    function get_shop_name_product($product)
    {
        if (!empty($product)) {
            if (!empty($product->shop_name) && ($product->user_role == 'admin' || $product->user_role == 'vendor')) {
                return html_escape($product->shop_name);
            } else {
                return html_escape($product->user_username);
            }
        }
    }
}

//get shop name by user id
if (!function_exists('get_shop_name_by_user_id')) {
    function get_shop_name_by_user_id($user_id)
    {
        $user = get_user($user_id);
        if (!empty($user)) {
            if (!empty($user->shop_name)) {
                return html_escape($user->shop_name);
            } else {
                return html_escape($user->username);
            }
        }
    }
}

//is multi-vendor active
if (!function_exists('is_multi_vendor_active')) {
    function is_multi_vendor_active()
    {
        $ci =& get_instance();
        $active = true;
        if ($ci->general_settings->multi_vendor_system != 1) {
            $active = false;
        }
        if ($ci->auth_check) {
            if ($ci->auth_user->role == "admin") {
                $active = true;
            }
        }
        return $active;
    }
}

//check is user vendor
if (!function_exists('is_user_vendor')) {
    function is_user_vendor()
    {
        $ci =& get_instance();
        if ($ci->auth_check && is_multi_vendor_active()) {
            if ($ci->general_settings->vendor_verification_system != 1) {
                return true;
            } else {
                if ($ci->auth_user->role == 'vendor' || $ci->auth_user->role == 'admin') {
                    return true;
                }
            }
        }
        return false;
    }
}

//is marketplace active
if (!function_exists('is_marketplace_active')) {
    function is_marketplace_active()
    {
        $ci =& get_instance();
        if ($ci->general_settings->marketplace_system == 1) {
            return true;
        }
        return false;
    }
}

//is bidding system active
if (!function_exists('is_bidding_system_active')) {
    function is_bidding_system_active()
    {
        $ci =& get_instance();
        if ($ci->general_settings->bidding_system == 1) {
            return true;
        }
        return false;
    }
}

//show cart
if (!function_exists('is_sale_active')) {
    function is_sale_active()
    {
        $ci =& get_instance();
        if (is_marketplace_active() || is_bidding_system_active()) {
            return true;
        }
        return false;
    }
}

//get translated message
if (!function_exists('trans')) {
    function trans($string)
    {
        $ci =& get_instance();
        if (!empty($ci->language_translations[$string])) {
            return $ci->language_translations[$string];
        }
        return "";
    }
}

//print old form data
if (!function_exists('old')) {
    function old($field)
    {
        $ci =& get_instance();
        if (isset($ci->session->flashdata('form_data')[$field])) {
            return html_escape($ci->session->flashdata('form_data')[$field]);
        }
    }
}

//count item
if (!function_exists('item_count')) {
    function item_count($items)
    {
        if (!empty($items)) {
            return count($items);
        }
        return 0;
    }
}

//admin url
if (!function_exists('admin_url')) {
    function admin_url()
    {
        $ci =& get_instance();
        return base_url() . $ci->routes->admin . '/';
    }
}
//get route
if (!function_exists('get_route')) {
    function get_route($key, $slash = false)
    {
        $ci =& get_instance();
        $route = $key;
        if (!empty($ci->routes->$key)) {
            $route = $ci->routes->$key;
            if ($slash == true) {
                $route .= '/';
            }
        }
        return $route;
    }
}

//get categories json
if (!function_exists('get_categories_json')) {
    function get_categories_json($lang_id)
    {
        $ci =& get_instance();
        return $ci->category_model->get_categories_json($lang_id);
    }
}

//get parent categories array
if (!function_exists('get_parent_categories_array')) {
    function get_parent_categories_array($category_id)
    {
        $ci =& get_instance();
        return $ci->category_model->get_parent_categories_array_by_category_id($category_id);
    }
}

//get mobile menu categories
if (!function_exists('get_mobile_menu_categories')) {
    function get_mobile_menu_categories()
    {
        $ci =& get_instance();
        return $ci->category_model->get_mobile_menu_categories();
    }
}

//get order
if (!function_exists('get_order')) {
    function get_order($order_id)
    {
        $ci =& get_instance();
        return $ci->order_model->get_order($order_id);
    }
}

//get order by order number
if (!function_exists('get_order_by_order_number')) {
    function get_order_by_order_number($order_number)
    {
        $ci =& get_instance();
        return $ci->order_model->get_order_by_order_number($order_number);
    }
}

//generate category url
if (!function_exists('generate_category_url')) {
    function generate_category_url($category)
    {

        if (!empty($category)) {
            if ($category->parent_id == 0) {
                return lang_base_url() . $category->slug;
            } else {
                return lang_base_url() . $category->parent_slug . "/" . $category->slug;
            }
        }
    }
}

//generate product url
if (!function_exists('generate_product_url')) {
    function generate_product_url($product)
    {
        if (!empty($product)) {
            return lang_base_url() . $product->slug;
        }
    }
}

//generate product url by slug
if (!function_exists('generate_product_url_by_slug')) {
    function generate_product_url_by_slug($slug)
    {
        if (!empty($slug)) {
            return lang_base_url() . $slug;
        }
    }
}

//generate blog url
if (!function_exists('generate_post_url')) {
    function generate_post_url($post)
    {
        if (!empty($post)) {
            return lang_base_url() . get_route("blog", true) . $post->category_slug . '/' . $post->slug;
        }
    }
}

//generate profile url
if (!function_exists('generate_profile_url')) {
    function generate_profile_url($slug)
    {
        if (!empty($slug)) {
            return lang_base_url() . get_route("profile", true) . $slug;
        }
    }
}

//generate static url
if (!function_exists('generate_url')) {
    function generate_url($route_1, $route_2 = null)
    {
        if (!empty($route_2)) {
            return lang_base_url() . get_route($route_1, true) . get_route($route_2);
        } else {
            return lang_base_url() . get_route($route_1);
        }
    }
}

//generate menu item url
if (!function_exists('generate_menu_item_url')) {
    function generate_menu_item_url($item)
    {
        if (!empty($item)) {
            return lang_base_url() . $item->slug;
        }
    }
}

//delete file from server
if (!function_exists('delete_file_from_server')) {
    function delete_file_from_server($path)
    {
        $full_path = FCPATH . $path;
        if (strlen($path) > 15 && file_exists($full_path)) {
            @unlink($full_path);
        }
    }
}

//get user avatar
if (!function_exists('get_user_avatar')) {
    function get_user_avatar($user)
    {
        if (!empty($user)) {
            if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
                return base_url() . $user->avatar;
            } elseif (!empty($user->avatar) && $user->user_type != "registered") {
                return $user->avatar;
            } else {
                return base_url() . "assets/img/user.png";
            }
        } else {
            return base_url() . "assets/img/user.png";
        }
    }
}

//get user avatar by id
if (!function_exists('get_user_avatar_by_id')) {
    function get_user_avatar_by_id($user_id)
    {
        $ci =& get_instance();

        $user = $ci->auth_model->get_user($user_id);
        if (!empty($user)) {
            if (!empty($user->avatar) && file_exists(FCPATH . $user->avatar)) {
                return base_url() . $user->avatar;
            } elseif (!empty($user->avatar) && $user->user_type != "registered") {
                return $user->avatar;
            } else {
                return base_url() . "assets/img/user.png";
            }
        } else {
            return base_url() . "assets/img/user.png";
        }
    }
}

//get user avatar by image url
if (!function_exists('get_user_avatar_by_image_url')) {
    function get_user_avatar_by_image_url($image_url, $user_type)
    {
        if (!empty($image_url)) {
            if ($user_type != "registered") {
                return $image_url;
            } else {
                return base_url() . $image_url;
            }
        } else {
            return base_url() . "assets/img/user.png";
        }
    }
}

//get review
if (!function_exists('get_review')) {
    function get_review($product_id, $user_id)
    {
        $ci =& get_instance();
        return $ci->review_model->get_review($product_id, $user_id);
    }
}

//calculate user rating
if (!function_exists('calculate_user_rating')) {
    function calculate_user_rating($user_id)
    {
        $ci =& get_instance();
        return $ci->review_model->calculate_user_rating($user_id);
    }
}

//date format
if (!function_exists('helper_date_format')) {
    function helper_date_format($datetime)
    {
        $date = date("j M Y", strtotime($datetime));
        $date = str_replace("Jan", trans("january"), $date);
        $date = str_replace("Feb", trans("february"), $date);
        $date = str_replace("Mar", trans("march"), $date);
        $date = str_replace("Apr", trans("april"), $date);
        $date = str_replace("May", trans("may"), $date);
        $date = str_replace("Jun", trans("june"), $date);
        $date = str_replace("Jul", trans("july"), $date);
        $date = str_replace("Aug", trans("august"), $date);
        $date = str_replace("Sep", trans("september"), $date);
        $date = str_replace("Oct", trans("october"), $date);
        $date = str_replace("Nov", trans("november"), $date);
        $date = str_replace("Dec", trans("december"), $date);
        return $date;
    }
}

//get logo
if (!function_exists('get_logo')) {
    function get_logo($settings)
    {
        if (!empty($settings)) {
            if (!empty($settings->logo) && file_exists(FCPATH . $settings->logo)) {
                return base_url() . $settings->logo;
            }
        }
        return base_url() . "assets/img/logo.svg";
    }
}

//get logo email
if (!function_exists('get_logo_email')) {
    function get_logo_email($settings)
    {
        if (!empty($settings)) {
            if (!empty($settings->logo_email) && file_exists(FCPATH . $settings->logo_email)) {
                return base_url() . $settings->logo_email;
            }
        }
        return base_url() . "assets/img/logo.png";
    }
}

//get favicon
if (!function_exists('get_favicon')) {
    function get_favicon($settings)
    {
        if (!empty($settings)) {
            if (!empty($settings->favicon) && file_exists(FCPATH . $settings->favicon)) {
                return base_url() . $settings->favicon;
            }
        }
        return base_url() . "assets/img/favicon.png";
    }
}

//get page title
if (!function_exists('get_page_title')) {
    function get_page_title($page)
    {
        if (!empty($page)) {
            return html_escape($page->title);
        } else {
            return "";
        }
    }
}

//get page description
if (!function_exists('get_page_description')) {
    function get_page_description($page)
    {
        if (!empty($page)) {
            return html_escape($page->description);
        } else {
            return "";
        }
    }
}

//get page keywords
if (!function_exists('get_page_keywords')) {
    function get_page_keywords($page)
    {
        if (!empty($page)) {
            return html_escape($page->keywords);
        } else {
            return "";
        }
    }
}

//get page by default name
if (!function_exists('get_page_by_default_name')) {
    function get_page_by_default_name($default_name, $lang_id)
    {
        $ci =& get_instance();
        return $ci->page_model->get_page_by_default_name($default_name, $lang_id);
    }
}

//get settings
if (!function_exists('get_settings')) {
    function get_settings()
    {
        $ci =& get_instance();
        $ci->load->model('settings_model');
        return $ci->settings_model->get_settings();
    }
}

//get general settings
if (!function_exists('get_general_settings')) {
    function get_general_settings()
    {
        $ci =& get_instance();
        $ci->load->model('settings_model');
        return $ci->settings_model->get_general_settings();
    }
}

//get form settings
if (!function_exists('get_form_settings')) {
    function get_form_settings()
    {
        $ci =& get_instance();
        $ci->load->model('settings_model');
        return $ci->settings_model->get_form_settings();
    }
}

//get product
if (!function_exists('get_product')) {
    function get_product($id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_product_by_id($id);
    }
}

//get available product
if (!function_exists('get_available_product')) {
    function get_available_product($id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_available_product($id);
    }
}

//get digital sale by buyer id
if (!function_exists('get_digital_sale_by_buyer_id')) {
    function get_digital_sale_by_buyer_id($buyer_id, $product_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_digital_sale_by_buyer_id($buyer_id, $product_id);
    }
}

//get digital sale by order id
if (!function_exists('get_digital_sale_by_order_id')) {
    function get_digital_sale_by_order_id($buyer_id, $product_id, $order_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_digital_sale_by_order_id($buyer_id, $product_id, $order_id);
    }
}

//get product image
if (!function_exists('get_product_image')) {
    function get_product_image($product_id, $size_name)
    {
        $ci =& get_instance();
        $image = $ci->file_model->get_image_by_product($product_id);
        if (empty($image)) {
            return base_url() . 'assets/img/no-image.jpg';
        } else {
            if ($image->storage == "aws_s3") {
                return $ci->aws_base_url . "uploads/images/" . $image->$size_name;
            } else {
                return base_url() . "uploads/images/" . $image->$size_name;
            }
        }
    }
}

//get product image url
if (!function_exists('get_product_image_url')) {
    function get_product_image_url($image, $size_name)
    {
        if ($image->storage == "aws_s3") {
            $ci =& get_instance();
            return $ci->aws_base_url . "uploads/images/" . $image->$size_name;
        } else {
            return base_url() . "uploads/images/" . $image->$size_name;
        }
    }
}

//get product images
if (!function_exists('get_product_images')) {
    function get_product_images($product_id)
    {
        $ci =& get_instance();
        return $ci->file_model->get_product_images($product_id);
    }
}

//get file manager image
if (!function_exists('get_file_manager_image')) {
    function get_file_manager_image($image)
    {
        $path = base_url() . 'assets/img/no-image.jpg';
        $ci =& get_instance();
        if (!empty($image)) {
            if ($image->storage == "aws_s3") {
                $path = $ci->aws_base_url . "uploads/images-file-manager/" . $image->image_path;
            } else {
                $path = base_url() . "uploads/images-file-manager/" . $image->image_path;
            }
        }
        return $path;
    }
}

//get variation main option image url
if (!function_exists('get_variation_main_option_image_url')) {
    function get_variation_main_option_image_url($option, $product_images = null)
    {
        $ci =& get_instance();
        $image_name = "";
        $storage = "";
        if (!empty($option)) {
            if ($option->is_default == 1 && !empty($product_images)) {
                foreach ($product_images as $product_image) {
                    if ($product_image->is_main == 1) {
                        $image_name = $product_image->image_small;
                        $storage = $product_image->storage;
                    }
                }
                if (empty($product_main_image)) {
                    foreach ($product_images as $product_image) {
                        $image_name = $product_image->image_small;
                        $storage = $product_image->storage;
                        break;
                    }
                }
            } else {
                $option_image = $ci->variation_model->get_variation_option_main_image($option->id);
                if (!empty($option_image)) {
                    $image_name = $option_image->image_small;
                    $storage = $option_image->storage;
                }
            }
        }
        if ($storage == "aws_s3") {
            return $ci->aws_base_url . "uploads/images/" . $image_name;
        } else {
            return base_url() . "uploads/images/" . $image_name;
        }
    }
}

//get variation option image url
if (!function_exists('get_variation_option_image_url')) {
    function get_variation_option_image_url($option_image)
    {
        $ci =& get_instance();
        if ($option_image->storage == "aws_s3") {
            return $ci->aws_base_url . "uploads/images/" . $option_image->image_small;
        } else {
            return base_url() . "uploads/images/" . $option_image->image_small;
        }
    }
}

//get product video url
if (!function_exists('get_product_video_url')) {
    function get_product_video_url($video)
    {
        $path = "";
        $ci =& get_instance();
        if (!empty($video)) {
            if ($video->storage == "aws_s3") {
                $path = $ci->aws_base_url . "uploads/videos/" . $video->file_name;
            } else {
                $path = base_url() . "uploads/videos/" . $video->file_name;
            }
        }
        return $path;
    }
}

//get product digital file url
if (!function_exists('get_product_digital_file_url')) {
    function get_product_digital_file_url($digital_file)
    {
        $path = "";
        $ci =& get_instance();
        if (!empty($digital_file)) {
            if ($digital_file->storage == "aws_s3") {
                $path = $ci->aws_base_url . "uploads/digital-files/" . $digital_file->file_name;
            } else {
                $path = base_url() . "uploads/digital-files/" . $digital_file->file_name;
            }
        }
        return $path;
    }
}

//get product audio url
if (!function_exists('get_product_audio_url')) {
    function get_product_audio_url($audio)
    {
        $path = "";
        $ci =& get_instance();
        if (!empty($audio)) {
            if ($audio->storage == "aws_s3") {
                $path = $ci->aws_base_url . "uploads/audios/" . $audio->file_name;
            } else {
                $path = base_url() . "uploads/audios/" . $audio->file_name;
            }
        }
        return $path;
    }
}

//get product count by category
if (!function_exists('get_products_count_by_category')) {
    function get_products_count_by_category($category_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_products_count_by_category($category_id);
    }
}

//get product count by subcategory
if (!function_exists('get_products_count_by_subcategory')) {
    function get_products_count_by_subcategory($category_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_products_count_by_subcategory($category_id);
    }
}

//get custom field
if (!function_exists('get_custom_field')) {
    function get_custom_field($field_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_field_joined($field_id);
    }
}

//get product custom field
if (!function_exists('get_product_custom_field')) {
    function get_product_custom_field($field_id, $product_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_product_custom_field($field_id, $product_id);
    }
}


//get custom field name by lang
if (!function_exists('get_custom_field_name_by_lang')) {
    function get_custom_field_name_by_lang($field_id, $lang_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_field_name_by_lang($field_id, $lang_id);
    }
}

//get custom field options
if (!function_exists('get_custom_field_options')) {
    function get_custom_field_options($field_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_field_options($field_id);
    }
}

//get custom field options by lang
if (!function_exists('get_custom_field_options_by_lang')) {
    function get_custom_field_options_by_lang($field_id, $lang_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_custom_field_options_by_lang($field_id, $lang_id);
    }
}

//get active product conditions
if (!function_exists('get_active_product_conditions')) {
    function get_active_product_conditions($lang_id)
    {
        $ci =& get_instance();
        return $ci->settings_model->get_active_product_conditions($lang_id);
    }
}

//get custom field option by lang
if (!function_exists('get_field_option_by_lang')) {
    function get_field_option_by_lang($common_id, $lang_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_field_option_by_lang($common_id, $lang_id);
    }
}

//get custom field value
if (!function_exists('get_custom_field_value')) {
    function get_custom_field_value($custom_field)
    {
        $str = "";
        if (!empty($custom_field)) {
            if (!empty($custom_field->field_value)) {
                $str = html_escape($custom_field->field_value);
            } elseif (!empty($custom_field->field_common_ids)) {
                $ci =& get_instance();
                foreach ($custom_field->field_common_ids as $item) {
                    $field_option = get_field_option_by_lang($item, $ci->selected_lang->id);
                    if (!empty($field_option)) {
                        if (empty($str)) {
                            $str = $field_option->field_option;
                        } else {
                            $str .= ", " . $field_option->field_option;
                        }
                    }
                }
            }
        }
        return $str;
    }
}

//get product wishlist count
if (!function_exists('get_product_wishlist_count')) {
    function get_product_wishlist_count($product_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_product_wishlist_count($product_id);
    }
}

//get product wishlist count
if (!function_exists('get_user_wishlist_products_count')) {
    function get_user_wishlist_products_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_wishlist_products_count($user_id);
    }
}

//get followers count
if (!function_exists('get_followers_count')) {
    function get_followers_count($following_id)
    {
        $ci =& get_instance();
        return $ci->profile_model->get_followers_count($following_id);
    }
}

//get following users count
if (!function_exists('get_following_users_count')) {
    function get_following_users_count($follower_id)
    {
        $ci =& get_instance();
        return $ci->profile_model->get_following_users_count($follower_id);
    }
}

//get user products count
if (!function_exists('get_user_products_count')) {
    function get_user_products_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_products_count($user_id);
    }
}

//get user products count
if (!function_exists('get_user_pending_products_count')) {
    function get_user_pending_products_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_pending_products_count($user_id);
    }
}

//get user drafts count
if (!function_exists('get_user_drafts_count')) {
    function get_user_drafts_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_drafts_count($user_id);
    }
}

//get user drafts count
if (!function_exists('get_user_downloads_count')) {
    function get_user_downloads_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_downloads_count($user_id);
    }
}

//get user hidden products count
if (!function_exists('get_user_hidden_products_count')) {
    function get_user_hidden_products_count($user_id)
    {
        $ci =& get_instance();
        return $ci->product_model->get_user_hidden_products_count($user_id);
    }
}
//get product comment count
if (!function_exists('get_product_comment_count')) {
    function get_product_comment_count($product_id)
    {
        $ci =& get_instance();
        return $ci->comment_model->get_product_comment_count($product_id);
    }
}

//get product variation options
if (!function_exists('get_product_variation_options')) {
    function get_product_variation_options($variation_id)
    {
        $ci =& get_instance();
        return $ci->variation_model->get_variation_options($variation_id);
    }
}

//get grouped shipping options
if (!function_exists('get_grouped_shipping_options')) {
    function get_grouped_shipping_options()
    {
        $ci =& get_instance();
        return $ci->settings_model->get_grouped_shipping_options();
    }
}

//get order shipping
if (!function_exists('get_order_shipping')) {
    function get_order_shipping($order_id)
    {
        $ci =& get_instance();
        return $ci->order_model->get_order_shipping($order_id);
    }
}

//get shipping option by lang
if (!function_exists('get_shipping_option_by_lang')) {
    function get_shipping_option_by_lang($common_id, $lang_id)
    {
        $ci =& get_instance();
        return $ci->settings_model->get_shipping_option_by_lang($common_id, $lang_id);
    }
}

//get shipping option by key
if (!function_exists('get_shipping_option_by_key')) {
    function get_shipping_option_by_key($key, $lang_id)
    {
        $ci =& get_instance();
        return $ci->settings_model->get_shipping_option_by_key($key, $lang_id);
    }
}

//get grouped product conditions
if (!function_exists('get_grouped_product_conditions')) {
    function get_grouped_product_conditions()
    {
        $ci =& get_instance();
        return $ci->settings_model->get_grouped_product_conditions();
    }
}

//get product condition by lang
if (!function_exists('get_product_condition_by_lang')) {
    function get_product_condition_by_lang($common_id, $lang_id)
    {
        $ci =& get_instance();
        return $ci->settings_model->get_product_condition_by_lang($common_id, $lang_id);
    }
}

//get product condition by key
if (!function_exists('get_product_condition_by_key')) {
    function get_product_condition_by_key($key, $lang_id)
    {
        $ci =& get_instance();
        return $ci->settings_model->get_product_condition_by_key($key, $lang_id);
    }
}

//check user follows
if (!function_exists('is_user_follows')) {
    function is_user_follows($following_id, $follower_id)
    {
        $ci =& get_instance();
        return $ci->profile_model->is_user_follows($following_id, $follower_id);
    }
}

//get blog post
if (!function_exists('get_post')) {
    function get_post($id)
    {
        $ci =& get_instance();
        return $ci->blog_model->get_post_joined($id);
    }
}

//get blog image url
if (!function_exists('get_blog_image_url')) {
    function get_blog_image_url($post, $size_name)
    {
        if ($post->storage == "aws_s3") {
            $ci =& get_instance();
            return $ci->aws_base_url . $post->$size_name;
        } else {
            return base_url() . $post->$size_name;
        }
    }
}

//get blog categories
if (!function_exists('get_blog_categories')) {
    function get_blog_categories()
    {
        $ci =& get_instance();
        return $ci->blog_category_model->get_categories();
    }
}

//get blog post count by category
if (!function_exists('get_blog_post_count_by_category')) {
    function get_blog_post_count_by_category($category_id)
    {
        $ci =& get_instance();
        return $ci->blog_model->get_post_count_by_category($category_id);
    }
}

//get subcomments
if (!function_exists('get_subcomments')) {
    function get_subcomments($parent_id)
    {
        $ci =& get_instance();
        return $ci->comment_model->get_subcomments($parent_id);
    }
}

//get unread conversations count
if (!function_exists('get_unread_conversations_count')) {
    function get_unread_conversations_count($receiver_id)
    {
        $ci =& get_instance();
        return $ci->message_model->get_unread_conversations_count($receiver_id);
    }
}

//get conversation unread messages count
if (!function_exists('get_conversation_unread_messages_count')) {
    function get_conversation_unread_messages_count($conversation_id)
    {
        $ci =& get_instance();
        return $ci->message_model->get_conversation_unread_messages_count($conversation_id);
    }
}

//get language
if (!function_exists('get_language')) {
    function get_language($lang_id)
    {
        $ci =& get_instance();
        return $ci->language_model->get_language($lang_id);
    }
}

//get countries
if (!function_exists('get_countries')) {
    function get_countries()
    {
        $ci =& get_instance();
        return $ci->location_model->get_countries();
    }
}

//get country
if (!function_exists('get_country')) {
    function get_country($id)
    {
        $ci =& get_instance();
        return $ci->location_model->get_country($id);
    }
}

//get state
if (!function_exists('get_state')) {
    function get_state($id)
    {
        $ci =& get_instance();
        return $ci->location_model->get_state($id);
    }
}

//get states by country
if (!function_exists('get_states_by_country')) {
    function get_states_by_country($country_id)
    {
        $ci =& get_instance();
        return $ci->location_model->get_states_by_country($country_id);
    }
}

//get ad codes
if (!function_exists('get_ad_codes')) {
    function get_ad_codes($ad_space)
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->ad_model->get_ad_codes($ad_space);
    }
}

//get recaptcha
if (!function_exists('generate_recaptcha')) {
    function generate_recaptcha()
    {
        $ci =& get_instance();
        if ($ci->recaptcha_status) {
            $ci->load->library('recaptcha');
            echo '<div class="form-group">';
            echo $ci->recaptcha->getWidget();
            echo $ci->recaptcha->getScriptTag();
            echo ' </div>';
        }
    }
}

//reset flash data
if (!function_exists('reset_flash_data')) {
    function reset_flash_data()
    {
        $ci =& get_instance();
        $ci->session->set_flashdata('errors', "");
        $ci->session->set_flashdata('error', "");
        $ci->session->set_flashdata('success', "");
    }
}

//get location
if (!function_exists('get_location')) {
    function get_location($object)
    {
        $ci =& get_instance();
        $location = "";
        if (!empty($object)) {
            if (!empty($object->address)) {
                $location = $object->address;
            }
            if (!empty($object->zip_code)) {
                $location .= " " . $object->zip_code;
            }
            if (!empty($object->city_id)) {
                $city = $ci->location_model->get_city($object->city_id);
                if (!empty($city)) {
                    if (!empty($object->address) || !empty($object->zip_code)) {
                        $location .= " ";
                    }
                    $location .= $city->name;
                }
            }
            if (!empty($object->state_id)) {
                $state = $ci->location_model->get_state($object->state_id);
                if (!empty($state)) {
                    if (!empty($object->address) || !empty($object->zip_code) || !empty($object->city_id)) {
                        $location .= ", ";
                    }
                    $location .= $state->name;
                }
            }
            if (!empty($object->country_id)) {
                $country = $ci->location_model->get_country($object->country_id);
                if (!empty($country)) {
                    if (!empty($object->state_id) || $object->city_id || !empty($object->address) || !empty($object->zip_code)) {
                        $location .= ", ";
                    }
                    $location .= $country->name;
                }
            }
        }
        return $location;
    }
}

//get location input
if (!function_exists('get_location_input')) {
    function get_location_input($country_id, $state_id, $city_id)
    {
        $ci =& get_instance();
        if (!empty($country_id) || !empty($state_id) || !empty($city_id)) {
            return $ci->location_model->get_location_input($country_id, $state_id, $city_id);
        }
        return "";
    }
}

//generate product keywords
if (!function_exists('generate_product_keywords')) {
    function generate_product_keywords($title)
    {
        $array = explode(" ", $title);
        $keywords = "";
        $c = 0;
        if (!empty($array)) {
            foreach ($array as $item) {
                $item = trim($item);
                $item = trim($item, ",");
                if (!empty($item)) {
                    $keywords .= $item;
                    if ($c > 0) {
                        $keywords .= ", ";
                    }
                }
                $c++;
            }
        }
        return $keywords;
    }
}

//set cached data by lang
if (!function_exists('set_cache_data')) {
    function set_cache_data($key, $data)
    {
        $ci =& get_instance();
        if ($ci->general_settings->cache_system == 1) {
            $ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
            $ci->cache->save($key, $data, $ci->general_settings->cache_refresh_time);
        }
    }
}

//get cached data by lang
if (!function_exists('get_cached_data')) {
    function get_cached_data($key)
    {
        $ci =& get_instance();
        if ($ci->general_settings->cache_system == 1) {
            $ci->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
            if ($data = $ci->cache->get($key)) {
                return $data;
            }
        }
        return false;
    }
}

//reset cache data
if (!function_exists('reset_cache_data')) {
    function reset_cache_data()
    {
        $ci =& get_instance();
        $path = $ci->config->item('cache_path');
        $cache_path = ($path == '') ? APPPATH . 'cache/' : $path;
        $handle = opendir($cache_path);
        while (($file = readdir($handle)) !== FALSE) {
            //Leave the directory protection alone
            if ($file != '.htaccess' && $file != 'index.html') {
                @unlink($cache_path . '/' . $file);
            }
        }
        closedir($handle);
    }
}

//reset user cache data
if (!function_exists('reset_user_cache_data')) {
    function reset_user_cache_data($user_id)
    {
        $ci =& get_instance();
        $path = $ci->config->item('cache_path');
        $cache_path = ($path == '') ? APPPATH . 'cache/' : $path;
        $handle = opendir($cache_path);
        while (($file = readdir($handle)) !== FALSE) {
            //Leave the directory protection alone
            if ($file != '.htaccess' && $file != 'index.html') {
                if (strpos($file, 'user' . $user_id . 'cache') !== false) {
                    @unlink($cache_path . '/' . $file);
                }
            }
        }
        closedir($handle);
    }
}

//reset product img cache data
if (!function_exists('reset_product_img_cache_data')) {
    function reset_product_img_cache_data($product_id)
    {
        $ci =& get_instance();
        $path = $ci->config->item('cache_path');
        $cache_path = ($path == '') ? APPPATH . 'cache/' : $path;
        $handle = opendir($cache_path);
        while (($file = readdir($handle)) !== FALSE) {
            //Leave the directory protection alone
            if ($file != '.htaccess' && $file != 'index.html') {
                if (strpos($file, 'img_product_' . $product_id) !== false) {
                    @unlink($cache_path . '/' . $file);
                }
            }
        }
        closedir($handle);
    }
}

//reset cache data on change
if (!function_exists('reset_cache_data_on_change')) {
    function reset_cache_data_on_change()
    {
        $ci =& get_instance();
        if ($ci->general_settings->refresh_cache_database_changes == 1) {
            reset_cache_data();
        }
    }
}

//cart product count
if (!function_exists('get_cart_product_count')) {
    function get_cart_product_count()
    {
        $ci =& get_instance();
        if (!empty($ci->session->userdata('mds_shopping_cart'))) {
            return @count($ci->session->userdata('mds_shopping_cart'));
        } else {
            return 0;
        }
    }
}

//date diff
if (!function_exists('date_difference')) {
    function date_difference($end_date, $start_date, $format = '%a')
    {
        $datetime_1 = date_create($end_date);
        $datetime_2 = date_create($start_date);
        $diff = date_diff($datetime_1, $datetime_2);
        $day = $diff->format($format) + 1;
        if ($start_date > $end_date) {
            $day = 0 - $day;
        }
        return $day;
    }
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

//get checkbox value
if (!function_exists('get_checkbox_value')) {
    function get_checkbox_value($input_post)
    {
        if (empty($input_post)) {
            return 0;
        } else {
            return 1;
        }
    }
}

//get product listing type
if (!function_exists('get_product_listing_type')) {
    function get_product_listing_type($product)
    {
        if (!empty($product)) {
            if ($product->listing_type == 'sell_on_site') {
                return trans("add_product_for_sale");
            }
            if ($product->listing_type == 'ordinary_listing') {
                return trans("add_product_services_listing");
            }
        }
    }
}

//get custom filters
if (!function_exists('get_custom_filters')) {
    function get_custom_filters($category_id)
    {
        $ci =& get_instance();
        return $ci->field_model->get_custom_filters($category_id);
    }
}

//get sess product filters
if (!function_exists('get_sess_product_filters')) {
    function get_sess_product_filters()
    {
        $ci =& get_instance();
        if (!empty($ci->session->userdata('mds_custom_product_filters'))) {
            return $ci->session->userdata('mds_custom_product_filters');
        }
        return null;
    }
}

//get filter name by key
if (!function_exists('get_filter_name_by_key')) {
    function get_filter_name_by_key($key)
    {
        if ($key == "search") {
            return trans("search");
        } else {
            $filters = get_sess_product_filters();
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter->product_filter_key == $key) {
                        return @html_escape($filter->name);
                        break;
                    }
                }
            }
        }
    }
}

//get filters query string array
if (!function_exists('get_filters_query_string_array')) {
    function get_filters_query_string_array()
    {
        $array = array();
        $str = $_SERVER["QUERY_STRING"];
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);

        @parse_str($str, $array);

        foreach ($array as $key => $value) {
            $array[$key] = xss_clean($array[$key]);
            $array[$key] = str_replace('"', '', $array[$key]);
            $array[$key] = str_replace('/', '', $array[$key]);
            $array[$key] = str_replace(']', '', $array[$key]);
            $array[$key] = str_replace('[', '', $array[$key]);
            $array[$key] = html_escape($array[$key]);
        }

        return $array;
    }
}

//get filter query string key value
if (!function_exists('get_filter_query_string_key_value')) {
    function get_filter_query_string_key_value($key)
    {
        $array = get_filters_query_string_array();
        if (!empty($array)) {
            return @html_escape($array[$key]);
        }
        return '';
    }
}

//is value exists in array
if (!function_exists('is_value_in_array')) {
    function is_value_in_array($value, $array)
    {
        if (empty($array)) {
            return false;
        }
        if (in_array($value, $array)) {
            return true;
        }
        return false;
    }
}

//get first value of array
if (!function_exists('get_first_array_value')) {
    function get_first_array_value($array)
    {
        if (empty($array)) {
            return '';
        }
        return html_escape(@$array[0]);
    }
}

//remove filter from query string
if (!function_exists('remove_filter_from_query_string')) {
    function remove_filter_from_query_string($filter_key)
    {
        $array = get_filters_query_string_array();
        $filter_key = clean_slug($filter_key);
        $url = base_url() . uri_string();
        $i = 0;
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                if ($filter_key == 'price') {
                    if ($key != 'p_min' && $key != 'p_max') {
                        if ($i == 0) {
                            $url .= '?' . $key . '=' . $value;
                        } else {
                            $url .= '&' . $key . '=' . $value;
                        }
                        $i++;
                    }
                } elseif ($filter_key == 'location') {
                    if ($key != 'country' && $key != 'state' && $key != 'city') {
                        if ($i == 0) {
                            $url .= '?' . $key . '=' . $value;
                        } else {
                            $url .= '&' . $key . '=' . $value;
                        }
                        $i++;
                    }
                } else {
                    if (($key != $filter_key)) {
                        if ($i == 0) {
                            $url .= '?' . $key . '=' . $value;
                        } else {
                            $url .= '&' . $key . '=' . $value;
                        }
                        $i++;
                    }
                }
            }
        }
        return $url;
    }
}

//create product filters query filter
if (!function_exists('create_product_filters_query_string')) {
    function create_product_filters_query_string()
    {
        $array = get_filters_query_string_array();
        $query_string = "";
        $i = 0;
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                if ($key != 'page') {
                    if ($i == 0) {
                        $query_string .= '?' . $key . '=' . $value;
                    } else {
                        $query_string .= '&' . $key . '=' . $value;
                    }
                    $i++;
                }
            }
        }
        return $query_string;
    }
}

//generate unique id
if (!function_exists('generate_unique_id')) {
    function generate_unique_id()
    {
        $id = uniqid("", TRUE);
        $id = str_replace(".", "-", $id);
        return $id . "-" . rand(10000000, 99999999);
    }
}

//generate short unique id
if (!function_exists('generate_short_unique_id')) {
    function generate_short_unique_id()
    {
        $id = uniqid("", TRUE);
        return str_replace(".", "-", $id);
    }
}
//generate order number
if (!function_exists('generate_transaction_number')) {
    function generate_transaction_number()
    {
        $transaction_number = uniqid("", TRUE);
        return str_replace(".", "-", $transaction_number);
    }
}

//generate token
if (!function_exists('generate_token')) {
    function generate_token()
    {
        $token = uniqid("", TRUE);
        $token = str_replace(".", "-", $token);
        return $token . "-" . rand(10000000, 99999999);
    }
}

//generate purchase code
if (!function_exists('generate_purchase_code')) {
    function generate_purchase_code()
    {
        $id = uniqid("", TRUE);
        $id = str_replace(".", "-", $id);
        $id .= "-" . rand(100000, 999999);
        $id .= "-" . rand(100000, 999999);
        return $id;
    }
}

//generate slug
if (!function_exists('str_slug')) {
    function str_slug($str)
    {
        $str = trim($str);
        return url_title(convert_accented_characters($str), "-", true);
    }
}

//clean slug
if (!function_exists('clean_slug')) {
    function clean_slug($slug)
    {
        $ci =& get_instance();
        $slug = urldecode($slug);
        $slug = $ci->security->xss_clean($slug);
        $slug = remove_special_characters($slug, true);
        return $slug;
    }
}

//clean number
if (!function_exists('clean_number')) {
    function clean_number($num)
    {
        $ci =& get_instance();
        $num = trim($num);
        $num = $ci->security->xss_clean($num);
        $num = intval($num);
        return $num;
    }
}

//clean string
if (!function_exists('clean_str')) {
    function clean_str($str)
    {
        $ci =& get_instance();
        $str = $ci->security->xss_clean($str);
        $str = remove_special_characters($str, false);
        return $str;
    }
}


//remove special characters
if (!function_exists('remove_special_characters')) {
    function remove_special_characters($str, $is_slug = false)
    {
        $str = trim($str);
        $str = str_replace('#', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('`', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('~', '', $str);
        if ($is_slug == true) {
            $str = str_replace(" ", '-', $str);
            $str = str_replace("'", '', $str);
        }
        return $str;
    }
}

//remove forbidden characters
if (!function_exists('remove_forbidden_characters')) {
    function remove_forbidden_characters($str)
    {
        $str = str_replace(';', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('`', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('~', '', $str);
        return $str;
    }
}

if (!function_exists('time_ago')) {
    function time_ago($timestamp)
    {
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        $minutes = round($seconds / 60);           // value 60 is seconds
        $hours = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec
        $days = round($seconds / 86400);          //86400 = 24 * 60 * 60;
        $weeks = round($seconds / 604800);          // 7*24*60*60;
        $months = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60
        $years = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60
        if ($seconds <= 60) {
            return trans("just_now");
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "1 " . trans("minute_ago");
            } else {
                return "$minutes " . trans("minutes_ago");
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return "1 " . trans("hour_ago");
            } else {
                return "$hours " . trans("hours_ago");
            }
        } else if ($days <= 30) {
            if ($days == 1) {
                return "1 " . trans("day_ago");
            } else {
                return "$days " . trans("days_ago");
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "1 " . trans("month_ago");
            } else {
                return "$months " . trans("months_ago");
            }
        } else {
            if ($years == 1) {
                return "1 " . trans("year_ago");
            } else {
                return "$years " . trans("years_ago");
            }
        }
    }
}

if (!function_exists('is_user_online')) {
    function is_user_online($timestamp)
    {
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        $minutes = round($seconds / 60);
        if ($minutes <= 2) {
            return true;
        } else {
            return false;
        }
    }
}

//print date
if (!function_exists('formatted_date')) {
    function formatted_date($timestamp)
    {
        return date("Y-m-d / H:i", strtotime($timestamp));
    }
}

//print formatted hour
if (!function_exists('formatted_hour')) {
    function formatted_hour($timestamp)
    {
        return date("H:i", strtotime($timestamp));
    }
}

if (!function_exists('convert_to_xml_character')) {
    function convert_to_xml_character($string)
    {
        return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
    }
}

//initialize iyzico
if (!function_exists('initialize_iyzico')) {
    function initialize_iyzico()
    {
        $ci =& get_instance();
        if ($ci->payment_settings->iyzico_enabled == 1) {
            require_once(APPPATH . 'third_party/iyzipay/vendor/autoload.php');
            require_once(APPPATH . 'third_party/iyzipay/vendor/iyzico/iyzipay-php/IyzipayBootstrap.php');
            IyzipayBootstrap::init();
            $options = new \Iyzipay\Options();
            $options->setApiKey($ci->payment_settings->iyzico_api_key);
            $options->setSecretKey($ci->payment_settings->iyzico_secret_key);
            if ($ci->payment_settings->iyzico_mode == "sandbox") {
                $options->setBaseUrl("https://sandbox-api.iyzipay.com");
            } else {
                $options->setBaseUrl("https://api.iyzipay.com");
            }
            return $options;
        }

    }
}

//iyzico create submerchant
if (!function_exists('iyzico_create_submerchant')) {
    function iyzico_create_submerchant($iyzico_submerchant)
    {
        $ci =& get_instance();
        $options = initialize_iyzico();
        $conversation_id = generate_short_unique_id();
        $data = array(
            'status' => 0,
            'merchant_key' => "",
            'error' => "",
        );

        # retrieve
        $request = new \Iyzipay\Request\RetrieveSubMerchantRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversation_id);
        $request->setSubMerchantExternalId($iyzico_submerchant["submerchant_id"]);
        $subMerchant = \Iyzipay\Model\SubMerchant::retrieve($request, $options);
        if ($subMerchant->getStatus() == "success") {
            $data["status"] = 1;
            $data["merchant_key"] = $subMerchant->getSubMerchantKey();
            return $data;
        }


        $request = new \Iyzipay\Request\CreateSubMerchantRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($conversation_id);
        $request->setSubMerchantExternalId($iyzico_submerchant["submerchant_id"]);
        $request->setAddress($iyzico_submerchant["address"]);
        $request->setContactName($iyzico_submerchant["first_name"]);
        $request->setContactSurname($iyzico_submerchant["last_name"]);
        $request->setEmail($iyzico_submerchant["email"]);
        $request->setGsmNumber($iyzico_submerchant["phone_number"]);
        $request->setName($iyzico_submerchant["submerchant_name"]);
        $request->setIban($iyzico_submerchant["iban"]);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);

        if ($iyzico_submerchant["submerchant_type"] == "PRIVATE_COMPANY") {
            $request->setSubMerchantType(\Iyzipay\Model\SubMerchantType::PRIVATE_COMPANY);
            $request->setTaxOffice($iyzico_submerchant["tax_office"]);
            $request->setLegalCompanyTitle($iyzico_submerchant["company_title"]);
        } elseif ($iyzico_submerchant["submerchant_type"] == "LIMITED_OR_JOINT_STOCK_COMPANY") {
            $request->setSubMerchantType(\Iyzipay\Model\SubMerchantType::LIMITED_OR_JOINT_STOCK_COMPANY);
            $request->setTaxOffice($iyzico_submerchant["tax_office"]);
            $request->setTaxNumber($iyzico_submerchant["tax_number"]);
            $request->setLegalCompanyTitle($iyzico_submerchant["company_title"]);
        } else {
            $request->setIdentityNumber($iyzico_submerchant["identity_number"]);
            $request->setSubMerchantType(\Iyzipay\Model\SubMerchantType::PERSONAL);
        }
        $subMerchant = \Iyzipay\Model\SubMerchant::create($request, $options);

        if ($subMerchant->getStatus() == "failure") {
            $data["error"] = $subMerchant->getErrorMessage();
        } else {
            $data["status"] = 1;
            $data["merchant_key"] = $subMerchant->getsubMerchantKey();
        }
        return $data;
    }
}
?>
