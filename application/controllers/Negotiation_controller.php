<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Negotiation_controller extends Home_Core_Controller
{

  public function __construct()
  {
    parent::__construct();
  }

  public function negotiation()
  {
    $user_id = $this->session->userdata("modesy_sess_user_id");
    $user = get_user($user_id);
    if ($user->role == 'member') {
      redirect('buyer_negotiation');
    } elseif ($user->role == 'vendor') {
      redirect('seller_negotiation');
    }
  }

  public function seller_negotiation()
  {
  }

  public function buyer_negotiation()
  {
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    // $products_id = array("00b1ac82-ffce-4c31-9161-6ed51c3b1de6");
    // $products_quantity = array(1);

    // $user_id = $this->session->userdata("modesy_sess_user_id");
    $conversation_id = $this->input->get('conv_id');

    $user_id = "0622b923-1ada-499f-8b6f-ca893a3d6094";

    $data['products'] = $this->negotiation_model->get_nego_products($user_id);
    if (!empty($conversation_id)) {
      $conversation = $this->negotiation_model->get_conversation($conversation_id);
      $data['conversation'] = $conversation;
      $data['messages'] = $this->negotiation_model->db_get_messages_by_conversation_id($conversation_id);
      $data['negotiations'] = $this->negotiation_model->db_get_negotiation_by_buyer_id_and_product_id($user_id, $conversation->product_id);
    }

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation', $data);
    $this->load->view('partials/_footer');
  }

  public function change_conversation()
  {
    // $user_id = $this->session->userdata('modesy_sess_user_id');
    $user_id = "0622b923-1ada-499f-8b6f-ca893a3d6094";
    $product_id = $this->input->get('product_id');
    $conversation = $this->negotiation_model->db_get_nego_conversation_by_user_and_product_id($user_id, $product_id);
    redirect("negotiation?conv_id=" . $conversation->id);
  }

  public function add_negotiation()
  {

    $quantity = $this->input->get('product_quantity');
    $product_id = $this->input->get('product_id');
    // $user_id = $this->session->userdata('modesy_sess_user_id');
    $user_id = "0622b923-1ada-499f-8b6f-ca893a3d6094";

    $this->negotiation_model->add_new_negotiation($user_id, $product_id, $quantity);

    $conversation = $this->negotiation_model->check_conversation($user_id, $product_id);

    $conversation_id = $conversation->id;
    redirect("negotiation?conv_id=" . $conversation_id);
  }

  public function delete_negotiation()
  {
    $nego_products = $this->session->userdata('nego_products_id');
    $nego_quantity = $this->session->userdata('nego_products_quantity');

    $product_id = $this->input->get('product_id');
    $conversation_id = $this->input->get('conv_id');

    $map_nego = $this->negotiation_model->delete_nego_product_and_quantity($nego_products, $nego_quantity, $product_id);
    $this->negotiation_model->delete_conversation($conversation_id);

    $this->session->set_userdata('nego_products_id', $map_nego["products_id"]);
    $this->session->set_userdata('nego_products_quantity', $map_nego["products_quantity"]);
    redirect('negotiation');
  }

  public function send_negotiation()
  {
    // $user_id = $this->session->userdata("modesy_sess_user_id");
    $user_id = "0622b923-1ada-499f-8b6f-ca893a3d6094";

    $product_id = $this->input->post("product_id");
    $nego_price = $this->input->post("nego_price");
    $shipping_cost = $this->input->post("shipping_cost");
    $quantity = $this->input->post("quantity");

    $this->negotiation_model->make_negotiation($user_id, $product_id, $nego_price, $shipping_cost, $quantity);
    redirect("negotiation");
  }
}
