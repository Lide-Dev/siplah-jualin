<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Negotiation_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function delete_nego_product_and_quantity($arr_products_id, $arr_products_quantity, $value)
  {
    $key = array_search($value, $arr_products_id);

    if ($key !== false) {
      unset($arr_products_id[$key]);
      unset($arr_products_quantity[$key]);
    }

    $map_products = [
      "products_id" => $arr_products_id,
      "products_quantity" => $arr_products_quantity
    ];

    return $map_products;
  }

  public function remap_negotiation_product($id, $quantity)
  {
    $raw_product = get_product($id);
    $total_price_with_ppn = calculate_total_compare($raw_product->price, $quantity, $raw_product->price * ($raw_product->vat_rate / 100));
    $total_price_without_ppn = calculate_total_compare($raw_product->price, $quantity);

    $product = new stdClass();
    $product->id = $raw_product->id;
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

  public function get_nego_products_by_id($arr_products_id, $arr_quantity)
  {
    $products = array();

    for ($i = 0; $i < count($arr_products_id); $i++) {
      $products[] = $this->remap_negotiation_product($arr_products_id[$i], $arr_quantity[$i]);
    }

    return $products;
  }

  public function remap_nego_conversation($conversation_id)
  {
    $raw_conversation = $this->get_nego_conversation_by_id($conversation_id);
    $raw_vendor = get_user($raw_conversation->receiver_id);
    $raw_user = get_user($raw_conversation->sender_id);

    $conversation = new stdClass();
    $conversation->id = $raw_conversation->id;
    $conversation->vendor_id = $raw_vendor->id;
    $conversation->vendor_username = $raw_vendor->username;
    $conversation->vendor_image = get_user_avatar($raw_vendor);
    $conversation->vendor_is_verified = $raw_vendor->email_status;
    $conversation->user_id = $raw_user->id;
    $conversation->user_username = $raw_user->username;
    $conversation->user_image = get_user_avatar($raw_user);
    $conversation->subject = $raw_conversation->subject;
    return $conversation;
  }

  public function get_all_nego_conversation_by_user($user_id)
  {
    $sql = "SELECT * FROM conversations WHERE sender_id = ?";
    $query = $this->db->query($sql, array($user_id));
    return $query->result();
  }

  public function get_nego_conversation_by_id($id)
  {
    $sql = "SELECT * FROM conversations WHERE id = ? LIMIT 1";
    $query = $this->db->query($sql, array($id));
    return $query->row();
  }

  public function get_nego_conversation_by_user_and_product_id($user_id, $product_id)
  {
    $sql = "SELECT * FROM conversations WHERE sender_id = ? AND product_id = ? LIMIT 1";
    $query = $this->db->query($sql, array($user_id, $product_id));
    return $query->row();
  }

  public function insert_nego_conversation($sender, $product)
  {
    $data = [
      "subject" => $product->title,
      "product_id" => $product->id,
      "sender_id" => $sender,
      "receiver_id" => $product->user_id
    ];
    $this->db->insert('conversations', $data);
  }

  public function delete_conversation($id)
  {
    $this->db->delete('conversation', array("id" => $id));
  }

  public function get_messages_by_conversation_id($id)
  {
    $sql = "SELECT * FROM conversation_messages WHERE conversation_id = ? ORDER BY created_at DESC";
    $query = $this->db->query($sql, array(clean_number($id)));
    return $query->result();
  }

  public function insert_message($user_id, $vendor_id, $conversation_id, $message)
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
