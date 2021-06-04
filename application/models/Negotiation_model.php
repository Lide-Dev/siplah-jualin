<?php
defined('BASEPATH') or exit('No direct script access allowed');
define('NEGOTIATION', 'negotiation');

require_once FCPATH . "application/models/Negotiation.php";
require_once FCPATH . "application/models/Conversation.php";


class Negotiation_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function open_conversation($conversation_id)
  {
    $conversation = $this->db_get_negotiation_conversation_by_id($conversation_id);
    $seller = get_user($conversation->seller_id);
    $buyer = get_user($conversation->buyer_id);
    $product = get_product($conversation->product_id);

    $m_conversation = new Conversation(
      $conversation->buyer_id,
      $conversation->seller_id,
      $conversation->product_id,
      $conversation->subject,
      $buyer->username,
      get_user_avatar($buyer),
      $seller->username,
      get_user_avatar($seller),
      $seller->email_status,
      $product,
      $this->db_get_messages_by_conversation_id($conversation_id),
      $conversation_id
    );

    return $m_conversation;
  }

  public function get_buyer_conversations($buyer_id)
  {
    $conversations = $this->db_get_negotiation_conversations_by_buyer_id($buyer_id);
    $arr_conversation = array();

    foreach ($conversations as $conversation) {
      $seller = get_user($conversation->seller_id);
      $buyer = get_user($conversation->buyer_id);
      $product = get_product($conversation->product_id);
      $last_nego = $this->db_get_latest_negotiation_by_conversation($conversation->id);
      $messages = $this->db_get_messages_by_conversation_id($conversation->id);

      $m_conversation = new Conversation(
        $conversation->buyer_id,
        $conversation->seller_id,
        $conversation->product_id,
        $conversation->subject,
        $buyer->username,
        get_user_avatar($buyer),
        $seller->username,
        get_user_avatar($seller),
        $seller->email_status,
        $product,
        $seller,
        $messages,
        $last_nego,
        $conversation->id
      );

      $arr_conversation[] = $m_conversation;
    }
    return $arr_conversation;
  }

  public function add_new_negotiation_conversation($buyer_id, $product_id, $quantity)
  {
    $conversation = $this->db_get_negotiation_conversation_by_buyer_id_and_product_id($buyer_id, $product_id);
    $product = get_product($product_id);
    $buyer = get_user($buyer_id);
    $seller = get_user($product->user_id);

    if (empty($conversation)) {
      $m_conversation = new Conversation(
        $buyer_id,
        $product->user_id,
        $product_id,
        $product->title,
        $buyer->username,
        get_user_avatar($buyer),
        $seller->username,
        get_user_avatar($seller),
        $seller->email_status
      );
      $this->db_insert_negotiation_conversation($m_conversation);
      $temp_conversation_id = $this->db_get_negotiation_conversation_by_buyer_id_and_product_id($buyer_id, $product_id)->id;

      $m_negotiation = new Negotiation(
        $temp_conversation_id,
        $product->price,
        $product->shipping_cost,
        $quantity
      );

      $this->db_insert_active_negotiation($m_negotiation);
    }
  }

  public function make_offer($price, $shipping, $conversation_id)
  {
    $negotiation = $this->db_get_latest_negotiation_by_conversation($conversation_id);

    $this->db_update_inactive_negotiation($negotiation);

    $m_negotiation = new Negotiation(
      $conversation_id,
      $price,
      $shipping,
      $negotiation->quantity
    );

    $this->db_insert_active_negotiation($m_negotiation);
  }

  /* Start DB Negotiations */
  private function db_get_negotiation_by_buyer_and_product($buyer_id, $product_id)
  {
    $sql = "SELECT * FROM negotiations WHERE buyer_id = ? AND product_id = ?";
    $query = $this->db->query($sql, array($buyer_id, $product_id));
    return $query->result();
  }

  private function db_get_latest_negotiation_by_conversation($conversation_id)
  {
    $sql = "SELECT * FROM negotiations 
    WHERE conversation_id = ?
    ORDER BY is_active DESC LIMIT 1";
    $query = $this->db->query($sql, [$conversation_id]);
    return $query->row();
  }

  private function db_insert_active_negotiation(Negotiation $negotiation)
  {
    $this->db->insert('negotiations', $negotiation->get_active_insert_data());
  }

  private function db_update_inactive_negotiation($negotiation)
  {
    $this->db->update(
      'negotiations',
      ["is_active" => 0],
      ['conversation_id' => $negotiation->conversation_id]
    );
  }
  /* END DB Negotiations */

  /* Start DB Conversations */
  private function db_get_negotiation_conversations_by_buyer_id($buyer_id)
  {
    $sql = "SELECT * FROM conversations WHERE buyer_id = ? AND type = ?";
    $query = $this->db->query($sql, [$buyer_id, NEGOTIATION]);
    return $query->result();
  }

  private function db_get_negotiation_conversation_by_buyer_id_and_product_id($buyer_id, $product_id)
  {
    $sql = "SELECT * FROM conversations WHERE buyer_id = ? AND product_id = ? AND type = ? LIMIT 1";
    $query = $this->db->query($sql, [$buyer_id, $product_id, NEGOTIATION]);
    return $query->row();
  }

  private function db_get_negotiation_conversation_by_id($conversation_id)
  {
    $sql = "SELECT * FROM conversations WHERE id = ? AND type = ?";
    $query = $this->db->query($sql, [$conversation_id, NEGOTIATION]);
    return $query->result();
  }

  private function db_insert_negotiation_conversation(Conversation $conversation)
  {
    $this->db->insert('conversations', $conversation->get_negotiation_insert_data());
  }
  /* END DB Conversations */

  /* Start DB Conversation Messages */
  private function db_get_messages_by_conversation_id($conversation_id)
  {
    $sql = "SELECT * FROM conversation_messages WHERE conversation_id = ?";
    $query = $this->db->query($sql, [$conversation_id]);
    return $query->result();
  }
  /* END DB Conversation Messages */
}
