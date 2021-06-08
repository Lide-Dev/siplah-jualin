<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compare_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function compare()
	{

		$data['title'] = "Perbandingan Produk";
		$data['description'] = "Membandingkan produk" . " - " . $this->app_name;
		$data['keywords'] = "compare-product" . "," . $this->app_name;
		$data['arr_payment_source'] = array("Dana Bos");

		// $arr_product_id = $this->session->userdata("compared_products_id");
		$arr_product_id = [17];
		$product_quantity = $this->session->userdata("compared_products_quantity");

		// $this->session->unset_userdata("compared_products_id");

		$data['products'] = $this->compare_model->get_compared_products_by_id($arr_product_id, $product_quantity);
		$data['product_quantity'] = $product_quantity;
		$data['list_vendors'] = $this->compare_model->get_all_vendors_except($arr_product_id);
		$data['temp_vendor'] = $this->session->userdata('temp_vendor');

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
		array_push($compared_products, $this->input->get("product_id"));
		$this->session->set_userdata("compared_products_id", $compared_products);
		$this->session->unset_userdata("temp_vendor");
		redirect(base_url('compare'));
	}

	public function delete_compared_product()
	{
		$compared_products = $this->session->userdata("compared_products_id");
		$deleted_compared_product = $this->input->get("compared_product_id");
		if ($deleted_compared_product != null) {
			$compared_products = $this->compare_model->delete_product($compared_products, $deleted_compared_product);
			$this->session->set_userdata("compared_products_id", $compared_products);
		}
		$this->session->unset_userdata("temp_vendor");
		redirect(base_url('compare'));
	}

	public function add_compared_vendor()
	{
		$vendor_id = $this->input->get("vendor_id");
		$this->session->set_userdata("temp_vendor", get_user($vendor_id));
		redirect('compare');
	}

	public function do_negotiation()
	{
		$compared_products_id = $this->session->userdata("compared_products_id");
		$user_id = "0622b923-1ada-499f-8b6f-ca893a3d6094";
		$quantity = 1;

		$product = get_product($compared_products_id[0]);

		$this->form_validation->set_rules("payment_source", "Sumber Dana", "required");

		if ($this->form_validation->run() == TRUE) {
			if ($product->price < 200000000) {
				if ($compared_products_id > 3) {
					$this->compare_model->insert_all_negotiation($compared_products_id, $user_id, $quantity);
					redirect('negotiation');
				} else {
					echo var_dump("else 50jt");
					$this->session->set_flashdata("compare_status", "<div class='alert alert-danger' role='alert'>Barang Kurang dari 3</div>");
				}
			} elseif ($product->price > 200000000) {
				if ($compared_products_id > 5) {
					$this->compare_model->insert_all_negotiation($compared_products_id, $user_id, $quantity);
					redirect('negotiation');
				} else {
					echo var_dump("else 200jt");
					$this->session->set_flashdata("compare_status", "<div class='alert alert-danger' role='alert'>Barang Kurang dari 5</div>");
				}
			}
		} else {
			redirect('compare');
		}
	}
}
