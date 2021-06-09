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

		$this->session->set_userdata('compared_products_id', ['00b1ac82-ffce-4c31-9161-6ed51c3b1de6']);

		$arr_product_id = $this->session->userdata("compared_products_id");
		$product_quantity = $this->session->userdata("compared_products_quantity");
		$product_quantity = $product_quantity ? $product_quantity : 1;


		// $this->session->unset_userdata("compared_products_id");

		$data['compare_base_product'] = get_product($arr_product_id[0]);
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
		$user_id = $this->session->userdata('modesy_sess_user_id');
		$product_quantity = $this->session->userdata("compared_products_quantity");
		$product_quantity = $product_quantity ? $product_quantity : 1;
		$base_product_price = get_product($compared_products_id[0])->price * $product_quantity;
		$payment_source = $this->input->get('');

		$this->compare_model->do_negotiation($compared_products_id, $product_quantity, $user_id);

		$this->session->unset_userdata("compared_products_id");
		$this->session->unset_userdata("temp_vendor");

		$payment_source = $this->session->userdata('compare_payment_source');

		if (empty($payment_source)) {
			$this->session->set_flashdata('payment_source_error', '<div class="alert alert-warning" role="alert">Pilih Sumber Dana terlebih dahulu</div>');
			redirect('compare');
		}
		if (($base_product_price > 500000000 && $base_product_price < 200000000) && $compared_products_id < 3) {
			$this->session->set_flashdata('total_compared_product', '<div class="alert alert-warning" role="alert">Produk yang dibandingkan kurang</div>');
			redirect('compare');
		} elseif ($base_product_price > 200000000 && $compared_products_id < 5) {
			$this->session->set_flashdata('total_compared_product', '<div class="alert alert-warning" role="alert">Produk yang dibandingkan kurang</div>');
			redirect('compare');
		}

		redirect('negotiation');
	}
}
