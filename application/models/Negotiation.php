<?php
define('NEGO_PENDING', 'pending');
define('NEGO_ACCEPT', 'accepted');
define('NEGO_DECLINE', 'declined');
define('NEGO_REPLACE', 'replaced');
class Negotiation
{
  public $id,
    $conversation_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $created_at,
    $is_active;

  public function __construct(
    $conversation_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $is_active = 0,
    $created_at = 0,
    $id = 0
  ) {
    $this->id = $id;
    $this->conversation_id = $conversation_id;
    $this->product_last_price = $product_last_price;
    $this->shipping_last_price = $shipping_last_price;
    $this->quantity = $quantity;
    $this->is_active = $is_active;
    $this->$created_at = $created_at;
  }

  public function get_active_insert_data($negotiator_id)
  {
    $data = [
      "conversation_id" => $this->conversation_id,
      "product_last_price" => $this->product_last_price,
      "shipping_last_price" => $this->shipping_last_price,
      "quantity" => $this->quantity,
      "negotiator_id" => $negotiator_id,
      "is_active" => true
    ];
    return $data;
  }
}
