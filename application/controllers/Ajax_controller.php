<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->review_limit = 6;
        $this->comment_limit = 6;
    }

    /*
    *------------------------------------------------------------------------------------------
    * SEARCH LOCATION
    *------------------------------------------------------------------------------------------
    */

    //search location
    public function search_location()
    {
        $input_value = $this->input->post('input_value', true);
        $input_value = remove_special_characters($input_value);
        $data = array(
            'result' => 0,
            'response' => ''
        );

        if (!empty($input_value)) {
            $response = '<ul>';
            $countries = $this->location_model->search_countries($input_value);
            if (!empty($countries)) {
                $data['result'] = 1;
                foreach ($countries as $country) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $country->id . '"><i class="icon-map-marker"></i>' . $country->name . '</a></li>';
                }
            }
            $states = $this->location_model->search_states($input_value);
            if (!empty($states)) {
                $data['result'] = 1;
                foreach ($states as $state) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $state->country_id . '" data-state="' . $state->id . '"><i class="icon-map-marker"></i>' . $state->name . ', ' . $state->country_name . '</a></li>';
                }
            }
            $cities = $this->location_model->search_cities($input_value);
            if (!empty($cities)) {
                $data['result'] = 1;
                foreach ($cities as $city) {
                    $response .= '<li><a href="javascript:void(0)" data-country="' . $city->country_id . '" data-state="' . $city->state_id . '" data-city="' . $city->id . '"><i class="icon-map-marker"></i>' . $city->name . ', ' . $city->state_name . ', ' . $city->country_name . '</a></li>';
                }
            }
            $response .= '</ul>';
            $data['response'] = $response;
        }
        echo json_encode($data);
    }

    //get countire
    public function get_countries()
    {
        $countries = $this->location_model->get_active_countries();
        $content = '<option value="">' . trans("all") . '</option>';
        if (!empty($countries)) {
            foreach ($countries as $country) {
                if (!empty($this->default_location_id) && $this->default_location_id == $country->id) {
                    $content .= '<option value="' . $country->id . '" selected>' . $country->name . '</option>';
                } else {
                    $content .= '<option value="' . $country->id . '">' . $country->name . '</option>';
                }
            }
        }
        $data = array(
            'result' => 1,
            'content' => $content
        );
        echo json_encode($data);
    }

    //get states
    public function get_states()
    {
        $country_id = $this->input->post('country_id', true);
        $states = $this->location_model->get_states_by_country($country_id);
        $status = 0;
        $content = '';
        if (!empty($states)) {
            $status = 1;
            $content = '<option value="">' . trans("state") . '</option>';
            foreach ($states as $item) {
                $content .= '<option value="' . $item->id . '">' . html_escape($item->name) . '</option>';
            }
        }
        $data = array(
            'result' => $status,
            'content' => $content
        );
        echo json_encode($data);
    }

    //get cities
    public function get_cities()
    {
        $state_id = $this->input->post('state_id', true);
        $cities = $this->location_model->get_cities_by_state($state_id);
        $status = 0;
        $content = '';
        if (!empty($cities)) {
            $status = 1;
            $content = '<option value="">' . trans("city") . '</option>';
            foreach ($cities as $item) {
                $content .= '<option value="' . $item->id . '">' . html_escape($item->name) . '</option>';
            }
        }
        $data = array(
            'result' => $status,
            'content' => $content
        );
        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * AJAX SEARCH
    *------------------------------------------------------------------------------------------
    */

    //ajax search
    public function ajax_search()
    {
        $lang_base_url = $this->input->post('lang_base_url', true);
        $search_type = $this->input->post('search_type', true);
        $input_value = $this->input->post('input_value', true);
        $input_value = remove_special_characters($input_value);

        $data = array(
            'result' => 0,
            'response' => ''
        );

        if (!empty($search_type) && !empty($input_value)) {
            if ($search_type == 'member') {
                $data['result'] = 1;
                $response = '<ul>';
                $members = $this->profile_model->search_members_limited($input_value);
                if (!empty($members)) {
                    foreach ($members as $member) {
                        $response .= '<li><a href="' . $lang_base_url . get_route("profile") . '/' . $member->slug . '">' . $member->username . '</a></li>';
                    }
                } else {
                    $response .= '<li><a href="' . $lang_base_url . get_route("members") . '?search=' . $input_value . '">' . $input_value . '</a></li>';
                }
                $response .= '</ul>';
                $data['response'] = $response;
            } else {
                $data['result'] = 1;
                $response = '<ul>';
                $products = $this->product_model->search_products($input_value);
                if (!empty($products)) {
                    foreach ($products as $product) {
                        $response .= '<li><a href="' . $lang_base_url . $product->slug . '">' . $product->title . '</a></li>';
                    }
                } else {
                    $response .= '<li><a href="' . $lang_base_url . get_route("products") . '?search=' . $input_value . '">' . $input_value . '</a></li>';
                }
                $response .= '</ul>';
                $data['response'] = $response;
            }
        }
        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * USER REVIEW FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //add user review
    public function add_user_review()
    {
        if ($this->general_settings->reviews != 1) {
            exit();
        }
        $seller_id = $this->input->post('seller_id', true);
        $review = $this->user_review_model->get_review_by_user($seller_id, $this->auth_user->id);
        if (!empty($review)) {
            echo "voted_error";
        } else {
            $this->user_review_model->add_review();
        }
    }

    //load more review
    public function load_more_user_review()
    {
        $seller_id = $this->input->post('seller_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->review_limit;
        $data["user"] = $this->auth_model->get_user($seller_id);
        $data["reviews"] = $this->user_review_model->get_limited_reviews($seller_id, $new_limit);
        $data['review_count'] = $this->user_review_model->get_review_count($seller_id);
        $data['review_limit'] = $new_limit;

        $this->load->view('profile/_user_reviews', $data);
    }

    //delete user review
    public function delete_user_review()
    {
        $id = $this->input->post('review_id', true);
        $this->user_review_model->delete_review($id);
    }

    /*
    *------------------------------------------------------------------------------------------
    * WISHLIST FUNCTIONS
    *------------------------------------------------------------------------------------------
    */


    /*
     *------------------------------------------------------------------------------------------
     * VARIATION FUNCTIONS
     *------------------------------------------------------------------------------------------
     */

    //select variation option
    public function select_product_variation_option()
    {
        $variation_id = $this->input->post('variation_id', true);
        $selected_option_id = $this->input->post('selected_option_id', true);
        $variation = $this->variation_model->get_variation($variation_id);
        $option = $this->variation_model->get_variation_option($selected_option_id);

        $data = array(
            'status' => 0,
            'html_content_slider' => "",
            'html_content_price' => "",
            'html_content_stock' => "",
            'stock_status' => 1,
        );
        if (!empty($variation) && !empty($option)) {
            $product = $this->product_model->get_product_by_id($variation->product_id);

            //slider content response
            if ($variation->show_images_on_slider) {
                $product_images = $this->variation_model->get_variation_option_images($selected_option_id);
                if (empty($product_images)) {
                    $product_images = $this->file_model->get_product_images($variation->product_id);
                }
                $vars = array(
                    "product" => $product,
                    "product_images" => $product_images
                );
                $data["html_content_slider"] = $this->load->view('product/details/_preview', $vars, true);
            }

            //price content response
            if ($variation->use_different_price == 1) {
                $price = $product->price;
                $discount_rate = $product->discount_rate;
                if (isset($option->price)) {
                    $price = $option->price;
                }
                if (isset($option->discount_rate)) {
                    $discount_rate = $option->discount_rate;
                }
                if (empty($price)) {
                    $price = $product->price;
                    $discount_rate = $product->discount_rate;
                }
                $vars = array(
                    "product" => $product,
                    "price" => $price,
                    "discount_rate" => $discount_rate
                );
                $data["html_content_price"] = $this->load->view('product/details/_price', $vars, true);
            }

            //stock content response
            $stock = $product->stock;
            if ($option->is_default != 1) {
                $stock = $option->stock;
            }
            if ($stock == 0) {
                $data["html_content_stock"] = '<span class="text-danger">' . trans("out_of_stock") . '</span>';
                $data["stock_status"] = 0;
            } else {
                $data["html_content_stock"] = '<span class="text-success">' . trans("in_stock") . '</span>';
            }
            $data["status"] = 1;

        }
        echo json_encode($data);
    }

    //get sub variation options
    public function get_sub_variation_options()
    {
        $variation_id = $this->input->post('variation_id', true);
        $selected_option_id = $this->input->post('selected_option_id', true);
        $subvariation = $this->variation_model->get_product_sub_variation($variation_id);
        $content = null;
        $data = array(
            'status' => 0,
            'subvariation_id' => "",
            'html_content' => ""
        );
        if (!empty($subvariation)) {
            $options = $this->variation_model->get_variation_sub_options($selected_option_id);
            if (!empty($options)) {
                $content .= '<option value="">' . trans("select") . '</option>';
                foreach ($options as $option) {
                    $option_name = get_variation_option_name($option->option_names, $this->selected_lang->id);
                    $content .= '<option value="' . $option->id . '">' . html_escape($option_name) . '</option>';
                }
            }
            $data["status"] = 1;
            $data["subvariation_id"] = $subvariation->id;
            $data["html_content"] = $content;
        }

        echo json_encode($data);
    }


    //add or remove wishlist
    public function add_remove_wishlist()
    {
        $product_id = $this->input->post('product_id', true);
        $this->product_model->add_remove_wishlist($product_id);
    }

    /*
    *------------------------------------------------------------------------------------------
    * PRODUCT COMMENTS FUNCTIONS
    *------------------------------------------------------------------------------------------
    */
    //add comment
    public function add_comment()
    {
        post_method();
        if ($this->general_settings->product_comments != 1) {
            exit();
        }
        $limit = $this->input->post('limit', true);
        $product_id = $this->input->post('product_id', true);

        if ($this->auth_check) {
            $this->comment_model->add_comment();
        } else {
            if ($this->recaptcha_verify_request()) {
                $this->comment_model->add_comment();
            }
        }

        if ($this->general_settings->comment_approval_system == 1) {
            $data = array(
                'type' => 'message',
                'html_content' => "<p class='comment-success-message'><i class='icon-check'></i>&nbsp;&nbsp;" . trans("msg_comment_sent_successfully") . "</p>"
            );
            echo json_encode($data);
        } else {
            $this->generate_comment_html_content($product_id, $limit);
        }
    }

    //load more comment
    public function load_more_comment()
    {
        post_method();
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->comment_limit;

        $this->generate_comment_html_content($product_id, $new_limit);
    }

    //delete comment
    public function delete_comment()
    {
        post_method();
        $id = $this->input->post('id', true);
        $product_id = $this->input->post('product_id', true);
        $limit = $this->input->post('limit', true);

        $comment = $this->comment_model->get_comment($id);
        if ($this->auth_check && !empty($comment)) {
            if ($this->auth_user->role == "admin" || $this->auth_user->id == $comment->user_id) {
                $this->comment_model->delete_comment($id);
            }
        }

        $this->generate_comment_html_content($product_id, $limit);
    }

    //load subcomment box
    public function load_subcomment_box()
    {
        $comment_id = $this->input->post('comment_id', true);
        $limit = $this->input->post('limit', true);
        $vars = array(
            "parent_comment" => $this->comment_model->get_comment($comment_id),
            "comment_limit" => $limit
        );
        $html_content = $this->load->view('product/details/_add_subcomment', $vars, true);
        $data = array(
            'type' => 'form',
            'html_content' => $html_content,
        );
        echo json_encode($data);
    }

    //generate comment html content
    private function generate_comment_html_content($product_id, $limit)
    {
        $vars = array(
            "product" => $this->product_model->get_product_by_id($product_id),
            "comment_count" => $this->comment_model->get_product_comment_count($product_id),
            "comments" => $this->comment_model->get_comments($product_id, $limit),
            "comment_limit" => $limit
        );
        $html_content = $this->load->view('product/details/_comments', $vars, true);
        $data = array(
            'type' => 'comments',
            'html_content' => $html_content,
        );
        echo json_encode($data);
    }


    /*
    *------------------------------------------------------------------------------------------
    * BLOG COMMENTS FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    /**
     * Add Blog Comment
     */
    public function add_blog_comment()
    {
        if ($this->general_settings->blog_comments != 1) {
            exit();
        }
        $post_id = $this->input->post('post_id', true);
        $limit = $this->input->post('limit', true);
        if ($this->auth_check) {
            $this->comment_model->add_blog_comment();
        } else {
            if ($this->recaptcha_verify_request()) {
                $this->comment_model->add_blog_comment();
            }
        }

        if ($this->general_settings->comment_approval_system == 1) {
            $data = array(
                'type' => 'message',
                'html_content' => "<p class='comment-success-message'><i class='icon-check'></i>&nbsp;&nbsp;" . trans("msg_comment_sent_successfully") . "</p>"
            );
            echo json_encode($data);
        } else {
            $this->generate_comment_blog_html_content($post_id, $limit);
        }
    }

    /**
     * Delete Blog Comment
     */
    public function delete_blog_comment()
    {
        $comment_id = $this->input->post('comment_id', true);
        $post_id = $this->input->post('post_id', true);
        $limit = $this->input->post('limit', true);

        $comment = $this->comment_model->get_blog_comment($comment_id);
        if ($this->auth_check && !empty($comment)) {
            if ($this->auth_user->role == "admin" || $this->auth_user->id == $comment->user_id) {
                $this->comment_model->delete_blog_comment($comment_id);
            }
        }
        $this->generate_comment_blog_html_content($post_id, $limit);
    }

    /**
     * Load More Comments
     */
    public function load_more_blog_comments()
    {
        $post_id = $this->input->post('post_id', true);
        $limit = $this->input->post('limit', true);
        $new_limit = $limit + $this->comment_limit;

        $this->generate_comment_blog_html_content($post_id, $new_limit);
    }

    //generate blog comment html content
    private function generate_comment_blog_html_content($post_id, $limit)
    {
        $vars = array(
            "comments" => $this->comment_model->get_blog_comments($post_id, $limit),
            "comment_post_id" => $post_id,
            "comments_count" => $this->comment_model->get_blog_comment_count($post_id),
            "comment_limit" => $limit
        );
        $html_content = $this->load->view('blog/_blog_comments', $vars, true);
        $data = array(
            'type' => 'comments',
            'html_content' => $html_content,
        );
        echo json_encode($data);
    }

    /*
    *------------------------------------------------------------------------------------------
    * EMAIL FUNCTIONS
    *------------------------------------------------------------------------------------------
    */

    //send email
    public function send_email()
    {
        $this->load->model("email_model");
        $email_type = $this->input->post('email_type', true);
        if ($email_type == 'contact') {
            $this->send_email_contact_message();
        } elseif ($email_type == 'new_order') {
            $this->send_email_new_order();
        } elseif ($email_type == 'new_product') {
            $this->send_email_new_product();
        } elseif ($email_type == 'order_shipped') {
            $this->send_email_order_shipped();
        } elseif ($email_type == 'new_message') {
            $this->send_email_new_message();
        } elseif ($email_type == 'email_general') {
            $this->send_email_general();
        }
    }

    //send email contact message
    public function send_email_contact_message()
    {
        if ($this->general_settings->send_email_contact_messages == 1) {
            $data = array(
                'subject' => trans("contact_message"),
                'to' => $this->general_settings->mail_options_account,
                'template_path' => "email/email_contact_message",
                'message_name' => $this->input->post('message_name', true),
                'message_email' => $this->input->post('message_email', true),
                'message_text' => $this->input->post('message_text', true)
            );
            $this->email_model->send_email($data);
        }
    }

    //send email order summary to user
    public function send_email_new_order()
    {
        if ($this->general_settings->send_email_buyer_purchase == 1) {
            $order_id = $this->input->post('order_id', true);
            $order_id = clean_number($order_id);
            $order = get_order($order_id);
            $order_products = $this->order_model->get_order_products($order_id);
            $order_shipping = get_order_shipping($order_id);
            if (!empty($order)) {
                //send to buyer
                $to = "";
                if (!empty($order_shipping)) {
                    $to = $order_shipping->shipping_email;
                }
                if ($order->buyer_type == "registered") {
                    $user = get_user($order->buyer_id);
                    if (!empty($user)) {
                        $to = $user->email;
                    }
                }
                $data = array(
                    'subject' => trans("email_text_thank_for_order"),
                    'order' => $order,
                    'order_products' => $order_products,
                    'to' => $to,
                    'template_path' => "email/email_new_order"
                );
                $this->email_model->send_email($data);

                //send to seller
                if (!empty($order_products)) {
                    $seller_ids = array();
                    foreach ($order_products as $order_product) {
                        $seller = get_user($order_product->seller_id);
                        if (!empty($seller)) {
                            if ($seller->send_email_when_item_sold == 1 && !in_array($seller->id, $seller_ids)) {
                                array_push($seller_ids, $seller->id);
                                $seller_order_products = $this->order_model->get_seller_order_products($order_id, $seller->id);
                                $data = array(
                                    'subject' => trans("you_have_new_order"),
                                    'order' => $order,
                                    'order_products' => $seller_order_products,
                                    'to' => $seller->email,
                                    'template_path' => "email/email_new_order_seller"
                                );
                                $this->email_model->send_email($data);
                            }
                        }
                    }
                }
            }
        }
    }

    //send email new product
    public function send_email_new_product()
    {
        if ($this->general_settings->send_email_new_product == 1) {
            $product_id = $this->input->post('product_id', true);
            $product = $this->product_model->get_product_by_id($product_id);
            if (!empty($product)) {
                $data = array(
                    'subject' => trans("email_text_new_product"),
                    'product_url' => generate_product_url($product),
                    'to' => $this->general_settings->mail_options_account,
                    'template_path' => "email/email_new_product"
                );
                $this->email_model->send_email($data);
            }
        }
    }

    //send email new message
    public function send_email_new_message()
    {
        $sender_id = $this->input->post('sender_id', true);
        $receiver_id = $this->input->post('receiver_id', true);
        $receiver = get_user($receiver_id);
        if (!empty($receiver) && !empty($sender_id)) {
            $data = array(
                'subject' => trans("you_have_new_message"),
                'to' => $receiver->email,
                'template_path' => "email/email_new_message",
                'message_sender' => "",
                'message_subject' => $this->input->post('message_subject', true),
                'message_text' => $this->input->post('message_text', true)
            );
            $sender = get_user($sender_id);
            if (!empty($sender)) {
                $data['message_sender'] = $sender->username;
            }
            $this->email_model->send_email($data);
        }
    }

    //send email order shipped
    public function send_email_order_shipped()
    {
        if ($this->general_settings->send_email_order_shipped == 1) {
            $order_product_id = $this->input->post('order_product_id', true);
            $order_product = $this->order_model->get_order_product($order_product_id);
            if (!empty($order_product)) {
                $order = get_order($order_product->order_id);
                if (!empty($order)) {
                    $to = $order->shipping_email;
                    if ($order->buyer_type == "registered") {
                        $user = get_user($order->buyer_id);
                        $to = $user->email;
                    }
                    $data = array(
                        'subject' => trans("your_order_shipped"),
                        'to' => $to,
                        'template_path' => "email/email_order_shipped",
                        'order' => $order,
                        'order_product' => $order_product
                    );
                    $this->email_model->send_email($data);
                }
            }
        }
    }

    //send email general
    public function send_email_general()
    {
        $data = array(
            'template_path' => "email/email_general",
            'to' => $this->input->post('to', true),
            'subject' => $this->input->post('subject', true),
            'email_content' => $this->input->post('email_content', true),
            'email_link' => $this->input->post('email_link', true),
            'email_button_text' => $this->input->post('email_button_text', true)
        );
        $this->email_model->send_email($data);
    }

}
