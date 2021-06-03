<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . "application/models/Negotiation.php";
require_once FCPATH . "application/models/Conversation.php";

class Negotiation_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function add_new_negotiation($user_id, $product_id, $quantity)
  {
    $product = get_product($product_id);
    $negotiation = $this->db_get_latest_negotiation($user_id, $product_id);
    if (empty($negotiation)) {
      $m_negotiation = new Negotiation(
        $user_id,
        $product->user_id,
        $product_id,
        $product->price,
        $product->shipping_cost,
        $quantity
      );
      $this->db_insert_negotiation($m_negotiation);
    }
  }

  public function make_negotiation($user_id, $product_id, $nego_price, $shipping_cost, $quantity)
  {
    $user = get_user($user_id);
    $product = get_product($product_id);

    if ($user->role == 'member') {
      $m_negotiation = new Negotiation(
        $user_id,
        $product->user_id,
        $product_id,
        $nego_price,
        $shipping_cost,
        $quantity
      );
      $this->db_insert_negotiation($m_negotiation);
    } else {
    }
  }

  public function check_conversation($user_id, $product_id)
  {
    $conversation = $this->db_get_nego_conversation_by_user_and_product_id($user_id, $product_id);
    if (empty($conversation)) {
      $product = get_product($product_id);

      $m_conversation = new Conversation(
        $user_id,
        $product->user_id,
        $product->id,
        $product->title
      );
      $this->db_insert_conversation($m_conversation);

      $conversation = $this->db_get_nego_conversation_by_user_and_product_id($user_id, $product_id);
    }
    return $conversation;
  }

  public function get_nego_products($user_id)
  {
    $negotiations = $this->db_get_negotiation_by_buyer_id($user_id);
    $arr_product = array();
    foreach ($negotiations as $negotiation) {
      $m_product = $this->remap_negotiation_product($negotiation->product_id, $negotiation->quantity, $negotiation->id);

      array_push($arr_product, $m_product);
    }

    return $arr_product;
  }

  public function remap_negotiation_product($id, $quantity, $negotiation_id)
  {
    $raw_product = get_product($id);
    $total_price_with_ppn = calculate_total_compare($raw_product->price, $quantity, $raw_product->price * ($raw_product->vat_rate / 100));
    $total_price_without_ppn = calculate_total_compare($raw_product->price, $quantity);

    $product = new stdClass();
    $product->id = $raw_product->id;
    $product->negotiation_id = $negotiation_id;
    $product->title = $raw_product->title;
    $product->category = get_category_by_id($raw_product->category_id)->description;
    $product->category_id = $raw_product->category_id;
    $product->unit_price_formatted = price_formatted($raw_product->price, $raw_product->currency);
    $product->ppn_formatted = price_formatted($raw_product->price * ($raw_product->vat_rate / 100), $raw_product->currency);
    $product->total_price_with_ppn = price_formatted($total_price_with_ppn, $raw_product->currency);
    $product->total_price_without_ppn = price_formatted($total_price_without_ppn, $raw_product->currency);
    $product->image = get_product_image($raw_product->id, "image_default");
    $product->ppn = $raw_product->vat_rate;
    $product->user_id = $raw_product->user_id;
    $product->vendor = get_vendor($raw_product->user_id);
    $product->slug = $raw_product->slug;
    $product->quantity = $quantity;
    $product->stock = $raw_product->stock;
    $product->vat_rate = $raw_product->vat_rate;
    return $product;
  }


  public function get_conversation($conversation_id)
  {
    $conversation = $this->db_get_nego_conversation_by_id($conversation_id);
    $user = get_user($conversation->sender_id);
    $seller = get_user($conversation->receiver_id);

    $m_conversation = new Conversation(
      $conversation->sender_id,
      $conversation->receiver_id,
      $conversation->product_id,
      $conversation->subject,
      $user->username,
      get_user_avatar($user),
      $seller->username,
      get_user_avatar($seller),
      $seller->email_status,
      $conversation->id
    );

    return $m_conversation;
  }

  public function db_get_negotiation_by_buyer_id($user_id)
  {
    $sql = "SELECT * FROM negotiations WHERE buyer_id = ?";
    $query = $this->db->query($sql, array($user_id));
    return $query->result();
  }

  public function db_get_negotiation_by_buyer_id_and_product_id($user_id, $product_id)
  {
    $sql = "SELECT * FROM negotiations WHERE buyer_id = ? AND product_id = ?";
    $query = $this->db->query($sql, array($user_id, $product_id));
    return $query->result();
  }

  public function db_get_all_nego_conversation_by_user($user_id)
  {
    $sql = "SELECT * FROM conversations WHERE sender_id = ?";
    $query = $this->db->query($sql, array($user_id));
    return $query->result();
  }

  public function db_get_nego_conversation_by_id($id)
  {
    $sql = "SELECT * FROM conversations WHERE id = ? LIMIT 1";
    $query = $this->db->query($sql, array($id));
    return $query->row();
  }

  public function db_get_nego_conversation_by_user_and_product_id($user_id, $product_id)
  {
    $sql = "SELECT * FROM conversations WHERE sender_id = ? AND product_id = ? LIMIT 1";
    $query = $this->db->query($sql, array($user_id, $product_id));
    return $query->row();
  }

  public function db_insert_conversation(Conversation $data)
  {
    $this->db->insert('conversations', $data->get_insert_data());
  }

  public function db_get_messages_by_conversation_id($id)
  {
    $sql = "SELECT * FROM conversation_messages WHERE conversation_id = ? ORDER BY created_at DESC";
    $query = $this->db->query($sql, array(clean_number($id)));
    return $query->result();
  }

  public function db_get_all_negotiation($user_id, $product_id)
  {
    $sql = "SELECT * FROM negotiations WHERE user_id = ? AND product_id = ?";
    $query = $this->db->query($sql, array($user_id, $product_id));
    return $query->result();
  }

  public function db_get_latest_negotiation($user_id, $product_id)
  {
    $sql = "SELECT * FROM negotiations WHERE user_id = ? AND product_id = ? ORDER BY created_at DESC LIMIT 1";
    $query = $this->db->query($sql, array($user_id, $product_id));
    return $query->row();
  }

  public function db_insert_negotiation(Negotiation $data)
  {
    $this->db->insert('negotiations', $data->get_insert_data());
  }

  public function db_delete_negotiation($id)
  {
    $this->db->delete('negotiations', array("id" => $id));
  }

  public function db_insert_message($user_id, $vendor_id, $conversation_id, $message)
  {
    $data = [
      "conversation_id" => $conversation_id,
      "sender_id" => $user_id,
      "receiver_id" => $vendor_id,
      "message" => $message
    ];
    $this->db->insert('conversation_messages', $data);
  }
}
