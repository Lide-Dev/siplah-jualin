<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . "application/models/Negotiation.php";
class Compare_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remap_product($id, $quantity)
	{
		$raw_product = get_product($id);
		$total_price = calculate_total_compare($raw_product->price, $quantity, $raw_product->price * 0.1);

		$product = new stdClass();
		$product->id = $raw_product->id;
		$product->title = $raw_product->title;
		$product->category = get_category_by_id($raw_product->category_id)->description;
		$product->category_id = $raw_product->category_id;
		$product->price_formatted = price_formatted($raw_product->price, $raw_product->currency);
		$product->ppn_formatted = price_formatted($raw_product->price * 0.1, $raw_product->currency);
		$product->total_price_with_ppn = price_formatted($total_price, $raw_product->currency);
		$product->image = get_product_image($raw_product->id, "image_default");
		$product->ppn = $raw_product->vat_rate;
		$product->vendor = get_vendor($raw_product->user_id);
		$product->slug = $raw_product->slug;
		return $product;
	}

	public function get_compared_products_by_id($arr_products_id, $quantity)
	{
		$products = array();
		foreach ($arr_products_id as $product_id) {
			$products[] = $this->remap_product($product_id, $quantity);
		}
		return $products;
	}

	public function get_all_vendors()
	{
		$sql = "SELECT * FROM users WHERE role = 'vendor'";
		$db = $this->db->query($sql);
		return $db->result();
	}

	public function delete_product($arr_products_id, $value)
	{
		$key = array_search($value, $arr_products_id);
		if ($key !== false) {
			unset($arr_products_id[$key]);
		}
		return $arr_products_id;
	}

	public function get_compared_only_vendor($arr_products_id)
	{
		$products = $arr_products_id;
		$products[] = 0;
		return $products;
	}

	public function insert_all_negotiation($arr_products_id, $user_id, $quantity)
	{
		foreach ($arr_products_id as $product_id) {
			$negotiation = $this->negotiation_model->db_get_negotiation_by_user_id_and_product_id($user_id, $product_id);
			$product = get_product($product_id);
			if (empty($negotiation)) {
				$m_negotiation = new Negotiation(
					$user_id,
					$product->user_id,
					$product_id,
					$product->price,
					$product->shipping_cost,
					$quantity
				);
				$this->negotiation_model->db_insert_negotiation($m_negotiation);
			}
			// dd($product->id);
			$conversation = $this->negotiation_model->db_get_nego_conversation_by_user_and_product_id($user_id, $product_id);
			if (empty($conversation)) {
				$m_conversation = new Conversation(
					$user_id,
					$product->user_id,
					$product_id,
					$product->title
				);
				$this->negotiation_model->db_insert_conversation($m_conversation);
			}
		}
	}
}
