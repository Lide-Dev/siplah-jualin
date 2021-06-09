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
    // $this->session->set_userdata('modesy_sess_user_id', '10a746b1-9d61-4c1e-a6cf-096c2cb359f7');
    // $this->session->set_userdata('modesy_sess_user_role', 'vendor');
    $this->session->set_userdata('modesy_sess_user_id', 'C25EF687-2759-4DF5-9D74-3C104CDF9E4E');
    $this->session->set_userdata('modesy_sess_user_role', 'member');
    $conversation_id = $this->session->flashdata('conversation_id');


    $user_role = $this->session->userdata("modesy_sess_user_role");
    if ($user_role == 'member') {
      $this->buyer_negotiation($conversation_id);
    } elseif ($user_role == 'vendor') {
      $this->seller_negotiation($conversation_id);
    }
  }

  public function buyer_negotiation($conversation_id)
  {
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    $user_id = $this->session->userdata('modesy_sess_user_id');

    $data['user_id'] = $user_id;

    $data['conversations'] = $this->negotiation_model->get_buyer_conversations($user_id);

    $data['messages'] = $this->negotiation_model->get_messages($conversation_id);

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation');
    $this->load->view('partials/_footer');
  }

  public function seller_negotiation($conversation_id)
  {
    $data['title'] = trans("negotiation");
    $data['description'] = trans("negotiation") . " - " . $this->app_name;
    $data['keywords'] = trans("negotiation") . "," . $this->app_name;

    $user_id = $this->session->userdata('modesy_sess_user_id');

    $data['user_id'] = $user_id;

    $data['conversations'] = $this->negotiation_model->get_seller_conversations($user_id);

    $conversation_id = $this->input->get('conversation_id');
    $data['messages'] = $this->negotiation_model->get_messages($conversation_id);

    $this->load->view('partials/_header', $data);
    $this->load->view('negotiation/negotiation');
    $this->load->view('partials/_footer');
  }

  public function add_negotiation_conversation()
  {
    $user_id = $this->session->userdata('modesy_sess_user_id');

    $product_id = $this->input->get("product_id");
    $quantity = $this->input->get('product_quantity');
    $conversation = $this->negotiation_model->add_new_negotiation_conversation($user_id, $product_id, $quantity);
    $this->session->set_flashdata('conversation_id', $conversation->id);
    redirect('negotiation');
  }

  public function make_offer()
  {
    $user_id = $this->session->userdata('modesy_sess_user_id');

    $conversation_id = $this->input->post('conversation_id');
    $offer_price = $this->input->post('offer_price');
    $offer_shipping = $this->input->post('offer_shipping');

    $this->negotiation_model->make_offer($offer_price, $offer_shipping, $conversation_id, $user_id);
    $this->session->set_flashdata('conversation_id', $conversation_id);
    redirect('negotiation');
  }

  public function send_message()
  {
    $user_role = $this->session->userdata('modesy_sess_user_role');

    $message = $this->input->post('message');
    $conversation_id = $this->input->post('conversation_id');

    $this->negotiation_model->send_message($conversation_id, $message, $user_role);
    $this->session->set_flashdata('conversation_id', $conversation_id);
    redirect('negotiation');
  }

  public function offer_accept()
  {
    $conversation_id = $this->input->get('conversation_id');
    $this->session->set_flashdata('conversation_id', $conversation_id);

    $this->negotiation_model->accept_offer($conversation_id);
    redirect('negotiation');
  }

  public function offer_decline()
  {
    $conversation_id = $this->input->get('conversation_id');
    $this->session->set_flashdata('conversation_id', $conversation_id);

    $this->negotiation_model->decline_offer($conversation_id);

    redirect('negotiation');
  }

  public function change_selected()
  {
    $conversation_id = $conversation_id = $this->input->get('conversation_id');
    $this->session->set_flashdata('conversation_id', $conversation_id);

    redirect('negotiation');
  }
}
