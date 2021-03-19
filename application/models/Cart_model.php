<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //add to cart
    public function add_to_cart($product)
    {
        $cart = $this->get_sess_cart_items();
        $quantity = $this->input->post('product_quantity', true);
        if ($product->product_type == "digital") {
            $quantity = 1;
        }
        $appended_variations = $this->get_selected_variations($product->id)->str;
        $options_array = $this->get_selected_variations($product->id)->options_array;
        $object = $this->get_product_price_and_stock($product, $options_array, $quantity);

        $item = new stdClass();
        $item->cart_item_id = generate_unique_id();
        $item->product_id = $product->id;
        $item->product_type = $product->product_type;
        $item->product_title = $product->title . " " . $appended_variations;
        $item->options_array = $options_array;
        $item->quantity = $quantity;
        $item->unit_price = $object->price_calculated;
        $item->total_price = $object->price_calculated * $quantity;
        $item->discount_rate = $object->discount_rate;
        $item->currency = $product->currency;
        $item->product_vat = calculate_vat($object->price_calculated, $product->vat_rate);
        $item->shipping_cost = $product->shipping_cost;
        $item->is_stock_available = $object->is_stock_available;
        $item->purchase_type = 'product';
        $item->quote_request_id = 0;
        array_push($cart, $item);

        $this->session->set_userdata('mds_shopping_cart', $cart);
    }

    //add to cart quote
    public function add_to_cart_quote($quote_request_id)
    {
        $this->load->model('bidding_model');
        $quote_request = $this->bidding_model->get_quote_request($quote_request_id);

        if (!empty($quote_request)) {
            $product = $this->product_model->get_product_by_id($quote_request->product_id);
            if (!empty($product)) {
                $cart = $this->get_sess_cart_items();
                $item = new stdClass();
                $item->cart_item_id = generate_unique_id();
                $item->product_id = $product->id;
                $item->product_type = $product->product_type;
                $item->product_title = $quote_request->product_title;
                $item->options_array = array();
                $item->quantity = $quote_request->product_quantity;
                $item->unit_price = $quote_request->price_offered / $quote_request->product_quantity;
                $item->total_price = $quote_request->price_offered;
                $item->currency = $quote_request->price_currency;
                $item->product_vat = 0;
                $item->shipping_cost = $quote_request->shipping_cost;
                $item->is_stock_available = 1;
                $item->purchase_type = 'bidding';
                $item->quote_request_id = $quote_request->id;
                array_push($cart, $item);

                $this->session->set_userdata('mds_shopping_cart', $cart);
                return true;
            }
        }
        return false;
    }

    //remove from cart
    public function remove_from_cart($cart_item_id)
    {
        $cart = $this->cart_model->get_sess_cart_items();
        if (!empty($cart)) {
            $new_cart = array();
            foreach ($cart as $item) {
                if ($item->cart_item_id != $cart_item_id) {
                    array_push($new_cart, $item);
                }
            }
            $this->session->set_userdata('mds_shopping_cart', $new_cart);
        }
    }

    //get selected variations
    public function get_selected_variations($product_id)
    {
        $object = new stdClass();
        $object->str = "";
        $object->options_array = array();

        $variations = $this->variation_model->get_product_variations($product_id);
        $str = "";
        if (!empty($variations)) {
            foreach ($variations as $variation) {
                $append_text = "";
                if (!empty($variation) && $variation->is_visible == 1) {
                    $variation_val = $this->input->post('variation' . $variation->id, true);
                    if (!empty($variation_val)) {

                        if ($variation->variation_type == "text" || $variation->variation_type == "number") {
                            $append_text = $variation_val;
                        } else {
                            //check multiselect
                            if (is_array($variation_val)) {
                                $i = 0;
                                foreach ($variation_val as $item) {
                                    $option = $this->variation_model->get_variation_option($item);
                                    if (!empty($option)) {
                                        if ($i == 0) {
                                            $append_text .= get_variation_option_name($option->option_names, $this->selected_lang->id);
                                        } else {
                                            $append_text .= " - " . get_variation_option_name($option->option_names, $this->selected_lang->id);
                                        }
                                        $i++;
                                        array_push($object->options_array, $option->id);
                                    }
                                }
                            } else {
                                $option = $this->variation_model->get_variation_option($variation_val);
                                if (!empty($option)) {
                                    $append_text .= get_variation_option_name($option->option_names, $this->selected_lang->id);
                                    array_push($object->options_array, $option->id);
                                }
                            }
                        }

                        if (empty($str)) {
                            $str .= "(" . get_variation_label($variation->label_names, $this->selected_lang->id) . ": " . $append_text;
                        } else {
                            $str .= ", " . get_variation_label($variation->label_names, $this->selected_lang->id) . ": " . $append_text;
                        }
                    }
                }
            }
            if (!empty($str)) {
                $str = $str . ")";
            }
        }
        $object->str = $str;

        return $object;
    }

    //get product price and stock
    public function get_product_price_and_stock($product, $options_array, $quantity)
    {
        $object = new stdClass();
        $object->price = 0;
        $object->discount_rate = 0;
        $object->price_calculated = 0;
        $object->is_stock_available = 0;

        if (!empty($product)) {
            $stock = $product->stock;
            $object->price = $product->price;
            $object->discount_rate = $product->discount_rate;
            if (!empty($options_array)) {
                foreach ($options_array as $option_id) {
                    $option = $this->variation_model->get_variation_option($option_id);
                    if (!empty($option)) {
                        $variation = $this->variation_model->get_variation($option->variation_id);
                        if ($variation->use_different_price == 1) {
                            if (isset($option->price)) {
                                $object->price = $option->price;
                            }
                            if (isset($option->discount_rate)) {
                                $object->discount_rate = $option->discount_rate;
                            }
                        }
                        if ($option->is_default != 1) {
                            $stock = $option->stock;
                        }
                    }
                }
            }

            if (empty($object->price)) {
                $object->price = $product->price;
                $object->discount_rate = $product->discount_rate;
            }
            $object->price_calculated = calculate_product_price($object->price, $object->discount_rate);

            if ($stock >= $quantity) {
                $object->is_stock_available = 1;
            }
        }
        return $object;
    }

    //get product shipping cost
    public function get_product_shipping_cost($cart_items, $product, $quantity)
    {
        $array_shipping_cost_ids = array();
        if (!empty($cart_items)) {
            foreach ($cart_items as $item) {
                if (in_array($item->product_id, $array_shipping_cost_ids)) {
                    return $product->shipping_cost_additional * $quantity;
                } else {
                    array_push($array_shipping_cost_ids, $item->product_id);
                    if ($quantity > 1) {
                        return $product->shipping_cost + ($product->shipping_cost_additional * ($quantity - 1));
                    } else {
                        return $product->shipping_cost * $quantity;
                    }
                }
            }
        }
    }

    //update cart product quantity
    public function update_cart_product_quantity($product_id, $cart_item_id, $quantity)
    {
        $cart = $this->get_sess_cart_items();
        if (!empty($cart)) {
            foreach ($cart as $item) {
                if ($item->cart_item_id == $cart_item_id) {
                    $item->quantity = $quantity;
                }
            }
        }
        $this->session->set_userdata('mds_shopping_cart', $cart);
    }

    //calculate cart total
    public function calculate_cart_total()
    {
        $cart = $this->get_sess_cart_items();
        $cart_total = new stdClass();
        $cart_total->subtotal = 0;
        $cart_total->vat = 0;
        $cart_total->shipping_cost = 0;
        $cart_total->total = 0;
        $cart_total->currency = $this->payment_settings->default_product_currency;

        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product = $this->product_model->get_product_by_id($item->product_id);
                if ($item->purchase_type == 'bidding') {
                    $this->load->model('bidding_model');
                    $quote_request = $this->bidding_model->get_quote_request($item->quote_request_id);
                    if (!empty($quote_request)) {
                        $cart_total->subtotal += $quote_request->price_offered;
                        $cart_total->shipping_cost += $quote_request->shipping_cost;
                        if ($this->form_settings->shipping != 1) {
                            $cart_total->shipping_cost = 0;
                        }
                    }
                } else {
                    $object = $this->get_product_price_and_stock($product, $item->options_array, $item->quantity);
                    $cart_total->subtotal += $object->price_calculated * $item->quantity;
                    $cart_total->vat += $item->product_vat;
                    $cart_total->shipping_cost += $item->shipping_cost;
                    if ($this->form_settings->shipping != 1) {
                        $cart_total->shipping_cost = 0;
                    }
                }
            }
        }
        $cart_total->total = $cart_total->subtotal + $cart_total->vat + $cart_total->shipping_cost;
        $this->session->set_userdata('mds_shopping_cart_total', $cart_total);
    }

    //calculate total vat
    public function calculate_total_vat($price_calculated, $vat_rate, $quantity)
    {
        if (!empty($price_calculated)) {
            $price = $price_calculated / 100;
            $vat = calculate_vat($price, $vat_rate);
            if (!is_int($vat)) {
                $vat = round($vat * $quantity, 2);
            }
            return $vat * 100;
        }
        return 0;
    }

    //get cart items session
    public function get_sess_cart_items()
    {
        $cart = array();
        $new_cart = array();
        if (!empty($this->session->userdata('mds_shopping_cart'))) {
            $cart = $this->session->userdata('mds_shopping_cart');
        }
        foreach ($cart as $cart_item) {
            $product = $this->product_model->get_available_product($cart_item->product_id);
            if (!empty($product)) {
                //if purchase type is bidding
                if ($cart_item->purchase_type == 'bidding') {
                    $this->load->model('bidding_model');
                    $quote_request = $this->bidding_model->get_quote_request($cart_item->quote_request_id);
                    if (!empty($quote_request) && $quote_request->status == 'pending_payment') {
                        $item = new stdClass();
                        $item->cart_item_id = $cart_item->cart_item_id;
                        $item->product_id = $product->id;
                        $item->product_type = $cart_item->product_type;
                        $item->product_title = $cart_item->product_title;
                        $item->options_array = $cart_item->options_array;
                        $item->quantity = $cart_item->quantity;
                        $item->unit_price = $quote_request->price_offered / $quote_request->product_quantity;
                        $item->total_price = $quote_request->price_offered;
                        $item->discount_rate = 0;
                        $item->currency = $product->currency;
                        $item->product_vat = 0;
                        $item->shipping_cost = $quote_request->shipping_cost;
                        $item->purchase_type = $cart_item->purchase_type;
                        $item->quote_request_id = $cart_item->quote_request_id;
                        $item->is_stock_available = 1;
                        if ($this->form_settings->shipping != 1) {
                            $item->shipping_cost = 0;
                        }
                        array_push($new_cart, $item);
                    }
                } else {
                    $object = $this->get_product_price_and_stock($product, $cart_item->options_array, $cart_item->quantity);
                    $price = calculate_product_price($product->price, $product->discount_rate);
                    $item = new stdClass();
                    $item->cart_item_id = $cart_item->cart_item_id;
                    $item->product_id = $product->id;
                    $item->product_type = $cart_item->product_type;
                    $item->product_title = $cart_item->product_title;
                    $item->options_array = $cart_item->options_array;
                    $item->quantity = $cart_item->quantity;
                    $item->unit_price = $object->price_calculated;
                    $item->total_price = $object->price_calculated * $cart_item->quantity;
                    $item->discount_rate = $object->discount_rate;
                    $item->currency = $product->currency;
                    $item->product_vat = $this->calculate_total_vat($object->price_calculated, $product->vat_rate, $cart_item->quantity);
                    $item->shipping_cost = $this->get_product_shipping_cost($cart, $product, $cart_item->quantity);
                    $item->purchase_type = $cart_item->purchase_type;
                    $item->quote_request_id = $cart_item->quote_request_id;
                    $item->is_stock_available = $object->is_stock_available;
                    if ($this->form_settings->shipping != 1) {
                        $item->shipping_cost = 0;
                    }
                    array_push($new_cart, $item);
                }
            }
        }
        $this->session->set_userdata('mds_shopping_cart', $new_cart);
        return $new_cart;
    }

    //set cart shipping address session
    public function set_sess_cart_shipping_address()
    {
        $std = new stdClass();
        $std->shipping_first_name = $this->input->post('shipping_first_name', true);
        $std->shipping_last_name = $this->input->post('shipping_last_name', true);
        $std->shipping_email = $this->input->post('shipping_email', true);
        $std->shipping_phone_number = $this->input->post('shipping_phone_number', true);
        $std->shipping_address_1 = $this->input->post('shipping_address_1', true);
        $std->shipping_address_2 = $this->input->post('shipping_address_2', true);
        $std->shipping_country_id = $this->input->post('shipping_country_id', true);
        $std->shipping_state = $this->input->post('shipping_state', true);
        $std->shipping_city = $this->input->post('shipping_city', true);
        $std->shipping_zip_code = $this->input->post('shipping_zip_code', true);
        $std->billing_first_name = $this->input->post('billing_first_name', true);
        $std->billing_last_name = $this->input->post('billing_last_name', true);
        $std->billing_email = $this->input->post('billing_email', true);
        $std->billing_phone_number = $this->input->post('billing_phone_number', true);
        $std->billing_address_1 = $this->input->post('billing_address_1', true);
        $std->billing_address_2 = $this->input->post('billing_address_2', true);
        $std->billing_country_id = $this->input->post('billing_country_id', true);
        $std->billing_state = $this->input->post('billing_state', true);
        $std->billing_city = $this->input->post('billing_city', true);
        $std->billing_zip_code = $this->input->post('billing_zip_code', true);
        $std->use_same_address_for_billing = $this->input->post('use_same_address_for_billing', true);
        if (!isset($std->use_same_address_for_billing)) {
            $std->use_same_address_for_billing = 0;
        }

        if ($std->use_same_address_for_billing == 1) {
            $std->billing_first_name = $std->shipping_first_name;
            $std->billing_last_name = $std->shipping_last_name;
            $std->billing_email = $std->shipping_email;
            $std->billing_phone_number = $std->shipping_phone_number;
            $std->billing_address_1 = $std->shipping_address_1;
            $std->billing_address_2 = $std->shipping_address_2;
            $std->billing_country_id = $std->shipping_country_id;
            $std->billing_state = $std->shipping_state;
            $std->billing_city = $std->shipping_city;
            $std->billing_zip_code = $std->shipping_zip_code;
        } else {
            if (empty($std->billing_first_name)) {
                $std->billing_first_name = $std->shipping_first_name;
            }
            if (empty($std->billing_last_name)) {
                $std->billing_last_name = $std->shipping_last_name;
            }
            if (empty($std->billing_email)) {
                $std->billing_email = $std->shipping_email;
            }
            if (empty($std->billing_phone_number)) {
                $std->billing_phone_number = $std->shipping_phone_number;
            }
            if (empty($std->billing_address_1)) {
                $std->billing_address_1 = $std->shipping_address_1;
            }
            if (empty($std->billing_address_2)) {
                $std->billing_address_2 = $std->shipping_address_2;
            }
            if (empty($std->billing_country_id)) {
                $std->billing_country_id = $std->shipping_country_id;
            }
            if (empty($std->billing_state)) {
                $std->billing_state = $std->shipping_state;
            }
            if (empty($std->billing_city)) {
                $std->billing_city = $std->shipping_state;
            }
            if (empty($std->billing_zip_code)) {
                $std->billing_zip_code = $std->shipping_zip_code;
            }
        }
        $this->session->set_userdata('mds_cart_shipping_address', $std);
    }

    //get cart shipping address session
    public function get_sess_cart_shipping_address()
    {
        if (!empty($this->session->userdata('mds_cart_shipping_address'))) {
            return $this->session->userdata('mds_cart_shipping_address');
        }
        $std = new stdClass();
        $row = null;

        if ($this->auth_check) {
            $row = $this->profile_model->get_user_shipping_address($this->auth_user->id);
        } else {
            $row = $this->profile_model->get_user_shipping_address(null);
        }
        $std->shipping_first_name = $row->shipping_first_name;
        $std->shipping_last_name = $row->shipping_last_name;
        $std->shipping_email = $row->shipping_email;
        $std->shipping_phone_number = $row->shipping_phone_number;
        $std->shipping_address_1 = $row->shipping_address_1;
        $std->shipping_address_2 = $row->shipping_address_2;
        $std->shipping_country_id = $row->shipping_country_id;
        $std->shipping_state = $row->shipping_state;
        $std->shipping_city = $row->shipping_city;
        $std->shipping_zip_code = $row->shipping_zip_code;
        $std->billing_first_name = $row->shipping_first_name;
        $std->billing_last_name = $row->shipping_last_name;
        $std->billing_email = $row->shipping_email;
        $std->billing_phone_number = $row->shipping_phone_number;
        $std->billing_address_1 = $row->shipping_address_1;
        $std->billing_address_2 = $row->shipping_address_2;
        $std->billing_country_id = $row->shipping_country_id;
        $std->billing_state = $row->shipping_state;
        $std->billing_city = $row->shipping_city;
        $std->billing_zip_code = $row->shipping_zip_code;
        $std->use_same_address_for_billing = 1;
        $this->session->unset_userdata('mds_cart_shipping_address');
        return $std;
    }

    //check cart has physical products
    public function check_cart_has_physical_product()
    {
        $cart_items = $this->get_sess_cart_items();
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product_type == 'physical') {
                    return true;
                }
            }
        }
        return false;
    }

    //check cart has digital products
    public function check_cart_has_digital_product()
    {
        $cart_items = $this->get_sess_cart_items();
        if (!empty($cart_items)) {
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product_type == 'digital') {
                    return true;
                }
            }
        }
        return false;
    }

    //unset cart items session
    public function unset_sess_cart_items()
    {
        if (!empty($this->session->userdata('mds_shopping_cart'))) {
            $this->session->unset_userdata('mds_shopping_cart');
        }
    }

    //get cart total session
    public function get_sess_cart_total()
    {
        $cart_total = new stdClass();
        if (!empty($this->session->userdata('mds_shopping_cart_total'))) {
            $cart_total = $this->session->userdata('mds_shopping_cart_total');
        }
        return $cart_total;
    }

    //set cart payment method option session
    public function set_sess_cart_payment_method()
    {
        $std = new stdClass();
        $std->payment_option = $this->input->post('payment_option', true);
        $std->terms_conditions = $this->input->post('terms_conditions', true);
        $this->session->set_userdata('mds_cart_payment_method', $std);
    }

    //get cart payment method option session
    public function get_sess_cart_payment_method()
    {
        if (!empty($this->session->userdata('mds_cart_payment_method'))) {
            return $this->session->userdata('mds_cart_payment_method');
        }
    }

    //unset cart payment method option session
    public function unset_sess_cart_payment_method()
    {
        if (!empty($this->session->userdata('mds_cart_payment_method'))) {
            $this->session->unset_userdata('mds_cart_payment_method');
        }
    }

    //unset cart shipping address session
    public function unset_sess_cart_shipping_address()
    {
        if (!empty($this->session->userdata('mds_cart_shipping_address'))) {
            $this->session->unset_userdata('mds_cart_shipping_address');
        }
    }


    //clear cart
    public function clear_cart()
    {
        $this->unset_sess_cart_items();
        $this->unset_sess_cart_payment_method();
        $this->unset_sess_cart_shipping_address();
    }
}
