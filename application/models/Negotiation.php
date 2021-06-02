<?php
class Negotiation
{
  public $id,
    $user_id,
    $seller_id,
    $product_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $created_at;

  public function __construct(
    $user_id,
    $seller_id,
    $product_id,
    $product_last_price,
    $shipping_last_price,
    $quantity,
    $created_at = 0,
    $id = 0
  ) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->seller_id = $seller_id;
    $this->product_id = $product_id;
    $this->product_last_price = $product_last_price;
    $this->shipping_last_price = $shipping_last_price;
    $this->quantity = $quantity;
    $this->$created_at = $created_at;
  }

  public function get_insert_data()
  {
    $data = [
      "user_id" => $this->user_id,
      "seller_id" => $this->seller_id,
      "product_id" => $this->product_id,
      "product_last_price" => $this->product_last_price,
      "shipping_last_price" => $this->shipping_last_price,
      "quantity" => $this->quantity
    ];
    return $data;
  }
}
