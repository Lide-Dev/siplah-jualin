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

		if (!empty($arr_products_id)) {
			foreach ($arr_products_id as $product_id) {
				$products[] = $this->remap_product($product_id, $quantity);
			}
		}

		return $products;
	}

	public function get_all_vendors_except($arr_vendor_id)
	{
		if (!empty($arr_vendor_id)) {
			$sql = "SELECT * FROM users WHERE role = 'vendor' AND id NOT IN ?";
			$db = $this->db->query($sql, [$arr_vendor_id]);
			return $db->result();
		}
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

	public function do_negotiation($arr_product_id, $product_quantity, $user_id)
	{
		foreach ($arr_product_id as $product_id) {
			$this->negotiation_model->add_new_negotiation_conversation($user_id, $product_id, $product_quantity);
		}
	}
}
