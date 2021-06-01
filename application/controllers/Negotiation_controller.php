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
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    $products_id = array("00b1ac82-ffce-4c31-9161-6ed51c3b1de6");
    $products_quantity = array(1);

    // $products_id = $this->session->userdata('nego_products_id');
    // $products_quantity = $this->session->userdata('nego_products_quantity');
    $conversation_id = $this->input->get('conv_id');

    $data['products'] = $this->negotiation_model->get_nego_products_by_id($products_id, $products_quantity);
    if (!empty($conversation_id)) {
      $data['conversation'] = $this->negotiation_model->remap_nego_conversation($conversation_id);
      $data['messages'] = $this->negotiation_model->get_messages_by_conversation_id($conversation_id);
    }

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation', $data);
    $this->load->view('partials/_footer');
  }

  public function change_conversation()
  {
    $user_id = $this->session->userdata('modesy_sess_user_id');
    $product_id = $this->input->get('product_id');
    $conversation_id = $this->negotiation_model->get_nego_conversation_by_user_and_product_id($user_id, $product_id)->id;
    redirect("negotiation?conv_id=" . $conversation_id);
  }

  public function add_negotiation()
  {
    $nego_products = $this->session->userdata('nego_products_id');
    $nego_quantities = $this->session->userdata('nego_products_quantity');

    $quantity = $this->input->get('product_quantity');
    $product_id = $this->input->get('product_id');
    $user_id = $this->session->userdata('modesy_sess_user_id');

    if (!empty($nego_products)) {
      if (!in_array($product_id, $nego_products)) {
        array_push($nego_products, $product_id);
        array_push($nego_quantities, $quantity);

        $this->session->set_userdata('nego_products_id', $nego_products);
        $this->session->set_userdata('nego_products_quantity', $nego_quantities);
      }
    } else {
      $nego_products[] = $product_id;
      $nego_quantities[] = $quantity;

      $this->session->set_userdata('nego_products_id', $nego_products);
      $this->session->set_userdata('nego_products_quantity', $nego_quantities);
    }

    $product = $this->negotiation_model->remap_negotiation_product($product_id, $quantity);

    $conversation = $this->negotiation_model->get_nego_conversation_by_user_and_product_id($user_id, $product_id);

    if (empty($conversation)) {
      $this->negotiation_model->insert_nego_conversation($user_id, $product);
      $conversation = $this->negotiation_model->get_nego_conversation_by_user_and_product_id($user_id, $product_id);
    }

    $conversation_id = $conversation->id;
    redirect("negotiation?conv_id=" . $conversation_id);
  }

  public function delete_negotiation()
  {
    $nego_products = $this->session->userdata('nego_products_id');
    $nego_quantity = $this->session->userdata('nego_products_quantity');

    $product_id = $this->input->get('nego_product_id');
    $conversation_id = $this->input->get('conv_id');

    $map_nego = $this->negotiation_model->delete_nego_product_and_quantity($nego_products, $nego_quantity, $product_id);
    $this->negotiation_model->delete_conversation($conversation_id);

    $this->session->set_userdata('nego_products_id', $map_nego["products_id"]);
    $this->session->set_userdata('nego_products_quantity', $map_nego["products_quantity"]);
    redirect('negotiation');
  }

  public function send_message()
  {
    $message = $this->input->post('message');
    $user_id = $this->session->userdata('modesy_sess_user_id');
    // $user_id = "e22ff369-5a3a-47c5-ba63-0683e872bd11";
    $vendor_id = $this->input->post('vendor_id');
    $conversation_id = $this->input->post('conversation_id');
  }
}
