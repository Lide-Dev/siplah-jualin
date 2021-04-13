<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if (!is_sale_active()) {
            redirect(lang_base_url());
        }
        $this->order_per_page = 15;
        $this->earnings_per_page = 15;
        $this->user_id = $this->auth_user->id;
    }

    /**
     * Orders
     */
    public function orders()
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "active_orders";

        $pagination = $this->paginate(generate_url("orders"), $this->order_model->get_orders_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_orders($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('order/orders', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Completed Orders
     */
    public function completed_orders()
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "completed_orders";

        $pagination = $this->paginate(generate_url("orders", "completed_orders"), $this->order_model->get_completed_orders_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_completed_orders($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('order/orders', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Order
     */
    public function order($order_number)
    {
        $data['title'] = trans("orders");
        $data['description'] = trans("orders") . " - " . $this->app_name;
        $data['keywords'] = trans("orders") . "," . $this->app_name;
        $data["active_tab"] = "";

        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect(lang_base_url());
        }
        if ($data["order"]->buyer_id != $this->user_id) {
            redirect(lang_base_url());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);
        $data["last_bank_transfer"] = $this->order_admin_model->get_bank_transfer_by_order_number($data["order"]->order_number);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('order/order', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Bank Transfer Payment Report Post
     */
    public function bank_transfer_payment_report_post()
    {
        $this->order_model->add_bank_transfer_payment_report();
        redirect($this->agent->referrer());
    }

    /**
     * Sales
     */
    public function sales()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "active_sales";
        $pagination = $this->paginate(generate_url("sales"), $this->order_model->get_sales_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_sales($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Need Confirmation
     */
    public function need_confirmation()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "need_confirmation";
        $pagination = $this->paginate(generate_url("sales", "need_confirmation"), $this->order_model->get_need_confirmation_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_need_confirmation($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * On Delivery
     */
    public function on_delivery()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "on_delivery";
        $pagination = $this->paginate(generate_url("sales", "on_delivery"), $this->order_model->get_on_delivery_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_on_delivery($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Not Yet Paid
     */
    public function not_yet_paid()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "not_yet_paid";
        $pagination = $this->paginate(generate_url("sales", "not_yet_paid"), $this->order_model->get_not_yet_paid_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_not_yet_paid($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Complaint
     */
    public function complaint()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "complaint";
        $pagination = $this->paginate(generate_url("sales", "complaint"), $this->order_model->get_complaint_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_complaint($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Completed Sales
     */
    public function completed_sales()
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "completed_sales";
        $pagination = $this->paginate(generate_url("sales", "completed_sales"), $this->order_model->get_completed_sales_count($this->user_id), $this->order_per_page);
        $data['orders'] = $this->order_model->get_paginated_completed_sales($this->user_id, $pagination['per_page'], $pagination['offset']);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sales', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Sale
     */
    public function sale($order_number)
    {
        if (!is_user_vendor()) {
            redirect(lang_base_url());
        }

        $data['title'] = trans("sales");
        $data['description'] = trans("sales") . " - " . $this->app_name;
        $data['keywords'] = trans("sales") . "," . $this->app_name;
        $data["active_tab"] = "";
        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect(lang_base_url());
        }
        if (!$this->order_model->check_order_seller($data["order"]->id)) {
            redirect(lang_base_url());
        }
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);
        

        $this->load->view('partials/_header', $data);
        $this->load->view('sale/sale', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Invoice
     */
    public function invoice($order_number)
    {
        $data['title'] = trans("invoice");
        $data['description'] = trans("invoice") . " - " . $this->app_name;
        $data['keywords'] = trans("invoice") . "," . $this->app_name;

        $data["order"] = $this->order_model->get_order_by_order_number($order_number);
        if (empty($data["order"])) {
            redirect(lang_base_url());
        }
        $data["invoice"] = $this->order_model->get_invoice_by_order_number($order_number);
        if (empty($data["invoice"])) {
            $this->order_model->add_invoice($data["order"]->id);
        }
        if (empty($data["invoice"])) {
            redirect(lang_base_url());
        }
        $data["invoice_items"] = unserialize($data["invoice"]->invoice_items);
        $data["order_products"] = $this->order_model->get_order_products($data["order"]->id);
        

        //check permission
        if ($this->auth_user->role != "admin") {
            $is_seller = false;
            if (!empty($data["order_products"])) {
                foreach ($data["order_products"] as $item) {
                    if ($item->seller_id == $this->auth_user->id) {
                        $is_seller = true;
                    }
                }
            }
            if ($this->auth_user->id != $data["order"]->buyer_id && $is_seller == false) {
                redirect(lang_base_url());
                exit();
            }
        }


        $this->load->view('order/invoice', $data);
    }


    /**
     * Update Order Product Status Post
     */
    public function update_order_product_status_post()
    {
        $id = $this->input->post('id', true);
        $order_product = $this->order_model->get_order_product($id);
        if (!empty($order_product)) {
            if ($this->order_model->update_order_product_status($id)) {

                //add digital sale if payment received
                $order_status = $this->input->post('order_status', true);
                if ($order_status == 'completed' || $order_status == 'payment_received') {
                    $this->order_model->add_digital_sale($order_product->product_id, $order_product->order_id);
                }
                $this->order_admin_model->update_payment_status_if_all_received($order_product->order_id);
                $this->order_admin_model->update_order_status_if_completed($order_product->order_id);

            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Add Shipping Tracking Number Post
     */
    public function add_shipping_tracking_number_post()
    {
        $id = $this->input->post('id', true);
        $order_product = $this->order_model->get_order_product($id);
        if (!empty($order_product)) {
            $this->order_model->add_shipping_tracking_number($id);
        }
        redirect($this->agent->referrer());
    }

    /**
     * Approve Order Product
     */
    public function approve_order_product_post()
    {
        $order_id = $this->input->post('order_product_id', true);
        $order_product_id = $this->input->post('order_product_id', true);
        if ($this->order_model->approve_order_product($order_product_id)) {
            //order product
            $order_product = $this->order_model->get_order_product($order_product_id);
            //add seller earnings
            $this->earnings_model->add_seller_earnings($order_product);
            //update order status
            $this->order_admin_model->update_order_status_if_completed($order_product->order_id);
        }
    }

}
