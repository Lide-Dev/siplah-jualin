<?php
class Conversation
{
  public $id,
    $user_id,
    $user_username,
    $user_img,
    $seller_id,
    $seller_username,
    $seller_img,
    $seller_is_verified,
    $product_id,
    $product,
    $seller,
    $messages,
    $subject;

  public function __construct(
    $user_id,
    $seller_id,
    $product_id,
    $subject,
    $user_username = "",
    $user_img = "",
    $seller_username = "",
    $seller_img = "",
    $seller_is_verified = false,
    $product = "",
    $seller = "",
    $messages = array(),
    $id = 0
  ) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->user_username = $user_username;
    $this->user_img = $user_img;
    $this->seller_id = $seller_id;
    $this->seller_username = $seller_username;
    $this->seller_img = $seller_img;
    $this->seller_is_verified = $seller_is_verified;
    $this->product_id = $product_id;
    $this->subject = $subject;
    $this->product = $product;
    $this->messages = $messages;
    $this->seller = $seller;
  }

  function get_negotiation_insert_data()
  {
    $data = [
      "subject" => $this->subject,
      "product_id" => $this->product_id,
      "buyer_id" => $this->user_id,
      "seller_id" => $this->seller_id,
      "type" => "negotiation"
    ];
    return $data;
  }
}
