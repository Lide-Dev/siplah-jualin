<?php
class Negotiation
{
  public $id,
    $buyer_id,
    $seller_id,
    $product_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $created_at,
    $is_active;

  public function __construct(
    $buyer_id,
    $seller_id,
    $product_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $is_active = 0,
    $created_at = 0,
    $id = 0
  ) {
    $this->id = $id;
    $this->buyer_id = $buyer_id;
    $this->seller_id = $seller_id;
    $this->product_id = $product_id;
    $this->product_last_price = $product_last_price;
    $this->shipping_last_price = $shipping_last_price;
    $this->quantity = $quantity;
    $this->is_active = $is_active;
    $this->$created_at = $created_at;
  }

  public function get_active_insert_data()
  {
    $data = [
      "buyer_id" => $this->buyer_id,
      "seller_id" => $this->seller_id,
      "product_id" => $this->product_id,
      "product_last_price" => $this->product_last_price,
      "shipping_last_price" => $this->shipping_last_price,
      "quantity" => $this->quantity,
      "is_active" => true
    ];
    return $data;
  }
}
