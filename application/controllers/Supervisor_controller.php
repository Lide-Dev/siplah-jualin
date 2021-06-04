<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Supervisor_controller extends Admin_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!is_supervisor()) {
            redirect(base_url());
        }
    }

    public function index()
    {
        $data['title'] = trans("admin_panel");

        $data['order_count'] = $this->order_admin_model->get_all_orders_count();
        $data['product_count'] = $this->product_admin_model->get_products_count();
        $data['pending_product_count'] = $this->product_admin_model->get_pending_products_count();
        $data['blog_posts_count'] = $this->blog_model->get_all_posts_count();
        $data['members_count'] = $this->auth_model->get_members_count();

        $data['latest_orders'] = $this->order_admin_model->get_orders_limited(15);

        $data['latest_pending_products'] = $this->product_admin_model->get_latest_pending_products(15);
        $data['latest_products'] = $this->product_admin_model->get_latest_products(15);

        $data['latest_reviews'] = $this->review_model->get_latest_reviews(15);
        $data['latest_comments'] = $this->comment_model->get_latest_comments(15);
        $data['latest_members'] = $this->auth_model->get_latest_members(6);


        $data['latest_transactions'] = $this->transaction_model->get_transactions_limited(15);
        $data['latest_promoted_transactions'] = $this->transaction_model->get_promoted_transactions_limited(15);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/index');
        $this->load->view('admin/includes/_footer');
    }
}
