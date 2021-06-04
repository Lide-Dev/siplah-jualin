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
    // $user_role = $this->session->userdata("modesy_sess_user_role");
    $user_role = 'member';
    if ($user_role == 'member') {
      $this->buyer_negotiation();
    } elseif ($user_role == 'vendor') {
      $this->seller_negotiation();
    }
  }

  public function buyer_negotiation()
  {
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    // $user_id = $this->session->userdata('modesy_sess_user_id');
    $user_id = "e22ff369-5a3a-47c5-ba63-0683e872bd11";
    $data['conversations'] = $this->negotiation_model->get_buyer_conversations($user_id);

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation');
    $this->load->view('partials/_footer');
  }

  public function seller_negotiation()
  {
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation');
    $this->load->view('partials/_footer');
  }

  public function add_negotiation_conversation()
  {
    $buyer_id = "e22ff369-5a3a-47c5-ba63-0683e872bd11";
    $product_id = $this->input->get("product_id");
    $quantity = $this->input->get('product_quantity');
    $this->negotiation_model->add_new_negotiation_conversation($buyer_id, $product_id, $quantity);
    redirect('negotiation');
  }

  public function open_conversation()
  {
    $user_role = $this->session->userdata('modesy_sess_user_role');
    $conversation_id = $this->input->post('conversation_id');

    if ($user_role == 'member') {
      $user_id = $this->session->userdata('modesy_sess_user_id');
      $conversation = $this->negotiation_model->open_conversation($conversation_id);
    } elseif ($user_role == 'vendor') {
      // Later
    }
  }

  public function make_offer()
  {
    $conversation_id = $this->input->post('conversation_id');
    $offer_price = $this->input->post('offer_price');
    $offer_shipping = $this->input->post('offer_shipping');

    $this->negotiation_model->make_offer($offer_price, $offer_shipping, $conversation_id);
    redirect('negotiation');
  }
}
