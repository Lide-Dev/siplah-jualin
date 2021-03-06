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
        $last_nego,
        $conversation->id
      );

      $arr_conversation[] = $m_conversation;
    }
    return $arr_conversation;
  }

  public function get_seller_conversations($seller_id)
  {
    $conversations = $this->db_get_negotiation_conversations_by_seller_id($seller_id);
    $arr_conversation = array();

    foreach ($conversations as $conversation) {
      $seller = get_user($conversation->seller_id);
      $buyer = get_user($conversation->buyer_id);
      $product = get_product($conversation->product_id);
      $last_nego = $this->db_get_latest_negotiation_by_conversation($conversation->id);

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
        $last_nego,
        $conversation->id
      );

      $arr_conversation[] = $m_conversation;
    }
    return $arr_conversation;
  }

  public function add_new_negotiation_conversation($user_id, $product_id, $quantity)
  {
    $conversation = $this->db_get_negotiation_conversation_by_buyer_id_and_product_id($user_id, $product_id);
    $product = get_product($product_id);
    $buyer = get_user($user_id);
    $seller = get_user($product->user_id);

    if (empty($conversation)) {
      $m_conversation = new Conversation(
        $user_id,
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
      $conversation = $this->db_get_negotiation_conversation_by_buyer_id_and_product_id($user_id, $product_id);

      $m_negotiation = new Negotiation(
        $conversation->id,
        $product->price,
        $product->shipping_cost,
        $quantity
      );
      $this->db_insert_active_negotiation($m_negotiation, $user_id);
    }
    return $conversation;
  }

  public function make_offer($price, $shipping, $conversation_id, $user_id)
  {
    $negotiation = $this->db_get_latest_negotiation_by_conversation($conversation_id);
    $conversation = $this->db_get_negotiation_conversation_by_id($conversation_id);
    $product = get_product($conversation->product_id);
    $user = get_user($user_id);

    $this->db_update_inactive_negotiation($negotiation);

    $m_negotiation = new Negotiation(
      $conversation_id,
      $price,
      $shipping,
      $negotiation->quantity
    );

    $message = "Anda menawar " . $product->title . " dengan harga :" . price_formatted($price, $product->currency);
    $this->send_message($conversation_id, $message, $user->role);

    $this->db_insert_active_negotiation($m_negotiation, $user_id);
  }

  public function send_message($conversation_id, $message, $user_role)
  {
    $conversation = $this->db_get_negotiation_conversation_by_id($conversation_id);

    if ($user_role == 'member') {
      $this->db_insert_message(
        $message,
        $conversation->buyer_id,
        $conversation->seller_id,
        $conversation_id
      );
    } elseif ($user_role == 'vendor') {
      $this->db_insert_message(
        $message,
        $conversation->seller_id,
        $conversation->buyer_id,
        $conversation_id
      );
    }
  }

  public function get_messages($conversation_id)
  {
    return $this->db_get_messages_by_conversation_id($conversation_id);
  }

  public function accept_offer($conversation_id)
  {
    $negotiation = $this->db_get_latest_negotiation_by_conversation($conversation_id);

    $this->db_update_negotiation_status_accepted($negotiation);
  }

  public function decline_offer($conversation_id)
  {
    $negotiation = $this->db_get_latest_negotiation_by_conversation($conversation_id);

    $this->db_update_negotiation_status_declined($negotiation);
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

  private function db_insert_active_negotiation(Negotiation $negotiation, $user_id)
  {
    $this->db->insert('negotiations', $negotiation->get_active_insert_data($user_id));
  }

  private function db_update_inactive_negotiation($negotiation)
  {
    $this->db->update(
      'negotiations',
      [
        "is_active" => 0,
        "status" => NEGO_REPLACE
      ],
      ['conversation_id' => $negotiation->conversation_id]
    );
  }

  private function db_update_negotiation_status_accepted($negotiation)
  {
    $this->db->update(
      'negotiations',
      ["status" => NEGO_ACCEPT],
      ["conversation_id" => $negotiation->conversation_id]
    );
  }

  private function db_update_negotiation_status_declined($negotiation)
  {
    $this->db->update(
      'negotiations',
      ["status" => NEGO_DECLINE],
      ["conversation_id" => $negotiation->conversation_id]
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

  private function db_get_negotiation_conversations_by_seller_id($seller_id)
  {
    $sql = "SELECT * FROM conversations WHERE seller_id = ? AND type = ?";
    $query = $this->db->query($sql, [$seller_id, NEGOTIATION]);
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
    return $query->row();
  }

  private function db_insert_negotiation_conversation(Conversation $conversation)
  {
    $this->db->insert('conversations', $conversation->get_negotiation_insert_data());
  }
  /* END DB Conversations */

  /* Start DB Conversation Messages */
  private function db_get_messages_by_conversation_id($conversation_id)
  {
    $sql = "SELECT * FROM conversation_messages WHERE conversation_id = ? ORDER BY created_at ASC";
    $query = $this->db->query($sql, [$conversation_id]);
    return $query->result();
  }

  private function db_insert_message($message, $sender_id, $receiver_id, $conversation_id)
  {
    $data = [
      "conversation_id" => $conversation_id,
      "sender_id" => $sender_id,
      "receiver_id" => $receiver_id,
      "message" => $message
    ];
    $this->db->insert('conversation_messages', $data);
  }
  /* END DB Conversation Messages */
}
