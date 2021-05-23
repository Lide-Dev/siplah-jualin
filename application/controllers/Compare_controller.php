<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compare_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('compare_model');
	}

	public function compare()
	{
		$arr_product_id = $this->session->userdata("compared_products_id");
		$product_quantity = $this->session->userdata("compared_products_quantity");

		$data['title'] = "Perbandingan Produk";
		$data['description'] = "membandingkan produk" . " - " . $this->app_name;
		$data['keywords'] = "compare-product" . "," . $this->app_name;
		$data['arr_payment_source'] = array("Dana Bos");
		$data['products'] = $this->compare_model->get_compared_products_by_id($arr_product_id, $product_quantity);
		$data['related_products'] = $this->compare_model->get_similar_product($arr_product_id[0]);

		$this->load->view('partials/_header', $data);
		$this->load->view('compare/compare', $data);
		$this->load->view('partials/_footer');
	}

	public function compared_product()
	{
		$product_id = $this->input->post("product_id");
		$product_quantity = $this->input->post("product_quantity");
		$this->session->set_userdata("compared_products_id", array($product_id));
		$this->session->set_userdata("compared_products_quantity", $product_quantity);
		redirect(base_url('compare'));
	}

	public function add_compared_product()
	{
		$compared_products = $this->session->userdata("compared_products_id");
		$compared_products[] = $this->input->get("product_id");
		$this->session->set_userdata("compared_products_id", $compared_products);
		redirect(base_url('compare'));
	}
}
