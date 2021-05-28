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
		$arr_id = [17, 18];
		$product_quantity = 2;

		$data['title'] = "Perbandingan Produk";
		$data['description'] = "membandingkan produk" . " - " . $this->app_name;
		$data['keywords'] = "compare-product" . "," . $this->app_name;
		$data['arr_payment_source'] = array("Dana Bos");
		$data['products'] = $this->compare_model->get_compared_products_by_id($arr_product_id, $product_quantity);
		$data['product_quantity'] = $product_quantity;
		$data['list_vendors'] = $this->compare_model->get_all_vendors();

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

	public function delete_compared_product()
	{
		$compared_products = $this->session->userdata("compared_products_id");
		$deleted_compared_product = $this->input->get("compared_product_id");
		$compared_products = $this->compare_model->delete_product($compared_products, $deleted_compared_product);
		$this->session->set_userdata("compared_products_id", $compared_products);
		redirect(base_url('compare'));
	}
}
