<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compare_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function remap_product($id, $quantity)
	{
		$raw_product = get_product($id);
		$total_price = $this->calculate_total_compare($raw_product->price, $quantity, $raw_product->price * 0.1);
		$product = new stdClass();
		$product->id = $raw_product->id;
		$product->title = $raw_product->title;
		$product->category = get_category_by_id($raw_product->category_id)->description;
		$product->category_id = $raw_product->category_id;
		$product->price_formatted = price_formatted($raw_product->price, $raw_product->currency);
		$product->ppn_formatted = price_formatted($raw_product->price * 0.1, $raw_product->currency);
		$product->total_price_with_ppn = price_formatted($total_price, $raw_product->currency);
		$product->image = get_product_image($raw_product->id, "image_default");
		return $product;
	}

	public function get_compared_products_by_id($arr_products, $quantity)
	{
		$products = array();
		foreach ($arr_products as $product_id) {
			$products[] = $this->remap_product($product_id, $quantity);
		}
		return $products;
	}

	public function calculate_total_compare($base_price, $quantity, $ppn = 0)
	{
		return ($base_price * $quantity) - ($ppn * $quantity);
	}

	public function get_similar_product($product_id)
	{
		$category_id = get_product($product_id)->category_id;
		return $this->product_model->get_similar_product_by_category($category_id);
	}
}
