<?php

use phpDocumentor\Reflection\Types\Null_;

defined('BASEPATH') or exit('No direct script access allowed');

class Product_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->review_limit = 5;
		$this->product_per_page = 18;
	}

	/**
	 * Start Selling
	 */
	public function start_selling()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(generate_url("settings", "update_profile"));
		}

		$data['title'] = trans("start_selling");
		$data['description'] = trans("start_selling") . " - " . $this->app_name;
		$data['keywords'] = trans("start_selling") . "," . $this->app_name;

		$this->load->view('partials/_header', $data);
		$this->load->view('product/start_selling', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Start Selling Post
	 */
	public function start_selling_post()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		$user_id = $this->input->post('id', true);
		$data = array(
			'is_active_shop_request' => 1,
			'shop_name' => remove_special_characters($this->input->post('shop_name', true)),
			//'country_id' => $this->input->post('country_id', true),
			//'state_id' => $this->input->post('state_id', true),
			'phone_number' => $this->input->post('phone_number', true),
			'about_me' => $this->input->post('about_me', true)
		);

		//is shop name unique
		if (!$this->auth_model->is_unique_shop_name($data['shop_name'], $user_id)) {
			$this->session->set_flashdata('form_data', $data);
			$this->session->set_flashdata('error', trans("msg_shop_name_unique_error"));
			redirect($this->agent->referrer());
		}

		if ($this->auth_model->add_shop_opening_requests($data)) {
			//send email
			$user = get_user($user_id);
			if (!empty($user) && $this->general_settings->send_email_shop_opening_request == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $this->general_settings->mail_options_account,
					'subject' => trans("shop_opening_request"),
					'email_content' => trans("there_is_shop_opening_request") . "<br>" . trans("user") . ": " . "<strong>" . $user->username . "</strong>",
					'email_link' => admin_url() . "shop-opening-requests",
					'email_button_text' => trans("view_details")
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}

			$this->session->set_flashdata('success', trans("msg_start_selling"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	public function init_specified_item($type, &$data)
	{
		if (!empty($_SESSION["ap_selected_book_type"]) && $_SESSION["ap_selected_book_type"] != $type) {
			unset($_SESSION["ap_selected_book_id"]);
			unset($_SESSION["ap_selected_book_type"]);
		}
		if (empty($_SESSION["ap_selected_book_id"])) {
			$query_parameter = ["page" => 1, "filter" => [], "search" => ""];
			$url_query = "";
			if (!empty($_GET["page"]) && is_numeric($_GET["page"]) && (int)$_GET["page"] > 0 && (int)$_GET["page"] <= 10) {
				$query_parameter["page"] = clean_number($_GET["page"]);
			}
			if (!empty($_GET["search"])) {
				$query_parameter["search"] = xss_clean($_GET["search"]);
				$url_query .= "?search={$query_parameter["search"]}";
			}
			$available_filter = [
				"text_book" => [
					"school_classes.name" => "filter_school_class",
					"classification_books.name" => "filter_classification",
					"school_levels.name" => "filter_school_level"
				],
				"non_text_book" => [
					// "school_classes.name" => "filter_school_class",
					"classification_books.name" => "filter_classification",
					"school_levels.name" => "filter_school_level"
				]
			];

			// $available_sort = [""]

			foreach ($available_filter[$type] as $key => $value) {
				if (!empty($_GET[$value])) {
					$query_parameter["filter"][$key] = xss_clean($_GET[$value]);
					$url_query .= (empty($url_query) ? "?" : "&") . "{$value}={$query_parameter["filter"][$key]}";
				}
			}
			$response = $this->book_model->get_paginated_books($query_parameter,  $type == "non_text_book");
			$response["url_query"] = $url_query;
			$data["books"] = $response;
			$data["classification"] = $this->book_model->get_classification(null, $type == "non_text_book");
			$data["school_class"] = $this->book_model->get_school_class();
			$data["school_level"] = $this->book_model->get_school_level(null, $type == "non_text_book");
		} else {
			$data["book"] = $this->book_model->get_books($_SESSION["ap_selected_book_id"], $type == "non_text_book");
		}
	}

	/**
	 * Add Product
	 */
	public function add_product($type = '', $type2 = '')
	{
		//check auth
		if (!empty($type) && !in_array($type, ["specified-item", "general-item", "service"])) {
			redirect(base_url("error-404"));
		}

		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(generate_url("start_selling"));
		}
		if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(generate_url("settings", "update_profile"));
		}

		$data['title'] = trans("sell_now");
		$data['description'] = trans("sell_now") . " - " . $this->app_name;
		$data['keywords'] = trans("sell_now") . "," . $this->app_name;
		$data["couriers"] = $this->product_model->get_couriers();
		$data['modesy_images'] = $this->file_model->get_sess_product_images_array();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();
		if (!empty($type2)) {
			if ($type2 == "text-book")
				$type .= "-text";
			else if ($type2 == "non-text-book")
				$type .= "-non-text";
		}
		$data["type"] = $type;



		switch ($type) {
			case 'specified-item-text':
				$category = $this->category_model->get_subcategories_by_parent_id("22");
				$this->init_specified_item("text_book", $data);
				break;
			case 'specified-item-non-text':
				$category = $this->category_model->get_subcategories_by_parent_id("23");
				$this->init_specified_item("non_text_book", $data);
				break;
			case 'general-item':
				$category = $this->category_model->get_subcategories_by_parent_id("9997");
				break;
			case 'service':
				$category = $this->category_model->get_subcategories_by_parent_id("2");
				break;
			case '':
				break;
			default:
				redirect(base_url("error-404"));
				break;
		}
		// dd($data);
		if (!empty($type))
			$data["category"] = $category;
		// dd($this->parent_categories);
		$this->load->view('partials/_header', $data);
		$this->load->view('product/add_product', $data);
		$this->load->view('partials/_footer');
	}

	public function select_book($type, $id)
	{
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(generate_url("start_selling"));
		}
		if (!in_array($type, ["text-book", "non-text-book"]))
			redirect(base_url("error-404"));

		$data["book"] = $this->book_model->get_books($id, $type == "non-text-book");
		// dd($id,$data["book"]);
		if (empty($data["book"])) {
			redirect(base_url("error-404"));
		}

		$this->session->set_tempdata("ap_selected_book_id", $id, 1800);
		$this->session->set_tempdata("ap_selected_book_type", $type == "non-text-book" ? "non_text_book" : "text_book", 1800);
		if ($type == "non-text-book")
			redirect(base_url("sell-now/specified-item/non-text-book"));
		else
			redirect(base_url("sell-now/specified-item/text-book"));
	}

	public function cancel_selected_book()
	{
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(generate_url("start_selling"));
		}

		// dd($id,$data["book"]);
		// if (empty($data["book"])) {
		// 	redirect(base_url("error-404"));
		// }
		$type = $_SESSION["ap_selected_book_type"];
		unset($_SESSION["ap_selected_book_type"]);
		unset($_SESSION["ap_selected_book_id"]);
		// dd('s');
		if ($type == "non_text_book")
			redirect(base_url("sell-now/specified-item/non-text-book"));
		else
			redirect(base_url("sell-now/specified-item/text-book"));
	}

	public function get_detail_book($type, $id)
	{
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(generate_url("start_selling"));
		}
		if (!in_array($type, ["text-book", "non-text-book"]))
			redirect(base_url("error-404"));

		$data["book"] = $this->book_model->get_books($id, $type == "non-text-book");
		// dd($data["book"]);
		// dd($id);
		if (empty($data["book"])) {
			redirect(base_url("error-404"));
		}
		// dd($data["book"]);
		$data['title'] = "Detail Buku - {$data["book"]->title}";
		$data['description'] = "Detail Buku {$data["book"]->title}" . " - " . $this->app_name;
		$data['keywords'] = "Detail Buku {$data["book"]->title}" . "," . $this->app_name;

		$this->load->view('partials/_header', $data);
		if ($type == "non-text-book") {
			$this->load->view('product/_catalog_non_text_book_detail', $data);
		} else {
			$this->load->view('product/_catalog_text_book_detail', $data);
		}
		$this->load->view('partials/_footer');
	}

	/**
	 * Add product according to type of product
	 *
	 * @return void
	 */
	public function add_product_post($type, $subtype = null)
	{
		if (!empty($type) && !in_array($type, ["specified-item", "general-item", "service"])) {
			redirect(base_url("error-404"));
		}
		if (!empty($subtype) && !in_array($subtype, ["text-book", "non-text-book"])) {
			redirect(base_url("error-404"));
		} else {
			if ($subtype == "text-book") {
				$type .= "-text";
				if ($_SESSION["ap_selected_book_type"] != "text_book") {
					$this->session->set_flashdata('error', "Terjadi kesalahan pada tipe form.");
					redirect("", "refresh");
				}
			} else if ($subtype == "non-text-book") {
				$type .= "-non-text";
				if ($_SESSION["ap_selected_book_type"] != "non_text_book") {
					$this->session->set_flashdata('error', "Terjadi kesalahan pada tipe form.");
					redirect("", "refresh");
				}
			}
		}		//check auth

		if (!$this->auth_check || !is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(generate_url("settings", "update_profile"));
		}
		switch ($type) {
			case 'specified-item-text':
				$type = $_SESSION["ap_selected_book_type"] == "text_book" ? "text-book" : "non-text-book";
				if ($this->add_specified_product_post()) {
					$this->session->set_flashdata('success', "Berhasil memasukkan produk baru!");
					redirect(base_url("sell-now/specified-item/$type"));
				} else {
					// $this->session->set_flashdata('error', "Terdapat kesalahan di form!");
					$this->add_product("specified-item", $type);
				} // $this->init_specified_item("text_book", $data);

				break;
			case 'specified-item-non-text':
				$type = $_SESSION["ap_selected_book_type"] == "text_book" ? "text-book" : "non-text-book";
				if ($this->add_specified_product_post()) {
					$this->session->set_flashdata('success', "Berhasil memasukkan produk baru!");
					redirect(base_url("sell-now/specified-item/$type"));
				} else {
					$this->add_product("specified-item", $type);
				}
				break;
			case 'general-item':
				if ($this->add_general_product_post()) {
					$this->session->set_flashdata('success', "Berhasil memasukkan produk baru!");
					redirect(base_url("sell-now/general-item"));
				} else {
					$this->add_product("general-item");
				}

				break;
			case 'service':
				if ($this->add_service_product_post()) {
					$this->session->set_flashdata('success', "Berhasil memasukkan produk baru!");
					redirect(base_url("sell-now/service"));
				} else {
					$this->add_product("service");
				}
				break;
			default:
				redirect(base_url("error-404"));
				break;
		}
	}

	public function default_add_product_validation($is_service = false)
	{
		// $this->form_validation->set_rules("category_product", "Kategori Produk", ["required", ["callback_category", [$this->category_model, "valid_category"]]]);
		$this->form_validation->set_rules("title", "Nama Produk", "required|max_length[500]");
		$this->form_validation->set_rules("minimum_order", "Minimal Pemesanan", "required|less_than_equal_to[999]|greater_than_equal_to[1]");
		$this->form_validation->set_rules("custom_category", "Kategori Buatana", "max_length[500]");
		$this->form_validation->set_rules("kbki", "Kode KBKI", "numeric");
		$this->form_validation->set_rules("description", "Kategori Buatan", "required|max_length[1000]");

		$this->form_validation->set_rules("category_homemade", "Kategori Buatan Indonesia", "callback_valid_checkbox");
		$this->form_validation->set_rules("category_umkm", "Kategori Barang UMKM", "callback_valid_checkbox");
		$this->form_validation->set_rules("visibility", "Produk Dimunculkan", "callback_valid_checkbox");
		$this->form_validation->set_rules("category_kemendikbud", "Kategori Barang Kemendikbud", "callback_valid_checkbox");
		$this->form_validation->set_rules("warranty", "Garansi", "required|max_length[254]");
		$this->form_validation->set_rules("sku", "Nomor SKU", "required|max_length[254]|is_unique[products.sku]");
		//----------
		if ($is_service) {
			$this->form_validation->set_rules("guarantee", "Jaminan Pelaksanaan Jasa", "required|max_length[254]");
		} else {
			$this->form_validation->set_rules("publisher", "Merek/Penerbit", "max_length[254]");
			$this->form_validation->set_rules("stock", "Minimal Pemesanan", "required|less_than_equal_to[999]|greater_than_equal_to[1]");
			$this->form_validation->set_rules("condition", "Kondisi Barang", "required|max_length[100]");
			$this->form_validation->set_rules("width", "Lebar", "required|numeric|less_than_equal_to[99999999]|greater_than_equal_to[1]");
			$this->form_validation->set_rules("length", "Panjang", "required|numeric|less_than_equal_to[99999999]|greater_than_equal_to[1]");
			$this->form_validation->set_rules("height", "Tinggi", "required|numeric|less_than_equal_to[99999999]|greater_than_equal_to[1]");
			$this->form_validation->set_rules("weight", "Berat", "required|numeric|less_than_equal_to[99999999]|greater_than_equal_to[1]");
			$this->form_validation->set_rules('availability_status', 'Status Ketersediaan', 'required|in_list[ready_stock,preorder]');
			$this->form_validation->set_rules("delivery_method", "Metode Pengiriman", "required|max_length[254]");
			$this->form_validation->set_rules("delivery_time", "Waktu Pengiriman", "required|max_length[254]");
			$this->form_validation->set_rules('delivery_assurance', 'Asuransi Pengiriman', 'max_length[254]');
		}
		// $this->form_validation->set_rules("courier", "Kurir Pengiriman", ["required", ["callback_courier", [$this->product_model, "valid_courier"]]]);

	}
	/**
	 * Add Product with type specified item Post
	 */
	public function add_specified_product_post()
	{
		$this->default_add_product_validation();
		if ($_SESSION["ap_selected_book_type"] == "non_text_book") {
			$this->form_validation->set_rules("price", "Harga", "required|numeric|greater_than_equal_to[1]");
		} else {
			$this->form_validation->set_rules("price_1", "Harga Zona 1", "required|numeric|greater_than_equal_to[1]");
			$this->form_validation->set_rules("price_2", "Harga Zona 2", "required|numeric|greater_than_equal_to[1]");
			$this->form_validation->set_rules("price_3", "Harga Zona 3", "required|numeric|greater_than_equal_to[1]");
			$this->form_validation->set_rules("price_4", "Harga Zona 4", "required|numeric|greater_than_equal_to[1]");
			$this->form_validation->set_rules("price_5", "Harga Zona 5", "required|numeric|greater_than_equal_to[1]");
		}

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('error', trans("msg_error"));
			// dd($this->form_validation->error_array());
			return false;
		} else {
			$this->db->trans_begin();
			$data = [
				"category_id" => $this->input->post("category_product"),
				"title" => $this->input->post("title"),
				"minimum_order" => $this->input->post("minimum_order"),
				"stock" => clean_number($this->input->post("stock")),
				"custom_category" => $this->input->post("custom_category"),
				"kbki" => clean_number($this->input->post("kbki")),
				"description" => html_escape($this->input->post("description")),
				"is_homemade" => filter_var($this->input->post("category_homemade"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_umkm_product" => filter_var($this->input->post("category_umkm"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_kemendikbud_product" => filter_var($this->input->post("category_kemendikbud"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"visibility" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"publisher" => $this->input->post("publisher") ?? "",
				"warranty" => $this->input->post("warranty"),
				"guarantee" => $this->input->post("delivery_assurance") ?? "", //https://lifepal.co.id/media/asuransi-pengiriman-barang/
				"product_condition" => $this->input->post("condition"),
				"sku" => $this->input->post("sku") ?? "",
				"width" => $this->input->post("width") ?? 0,
				"length" => $this->input->post("length") ?? 0,
				"height" => $this->input->post("height") ?? 0,
				"weight" => $this->input->post("weight") ?? 0,
				"shipping_courier_id" => $this->input->post("courier"),
				"shipping_method" => $this->input->post("delivery_method"),
				"shipping_time" => $this->input->post("delivery_time"),
				"catalog_id" => $_SESSION["ap_selected_book_id"],
				"catalog_type" => $_SESSION["ap_selected_book_type"],
				"type_product_id" => 1, //Buku
				"status_product_id" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 2,
				"currency" => "IDR",
				"user_id" => $this->auth_user->id,
				"availability_status" => $this->input->post("availability_status")
			];
			if ($_SESSION["ap_selected_book_type"] == "non_text_book") {
				$data["price"] = clean_number($this->input->post("price"));
			} else {
				$data["price"] = json_encode([
					"zone_1" => clean_number($this->input->post("price_1")),
					"zone_2" => clean_number($this->input->post("price_2")),
					"zone_3" => clean_number($this->input->post("price_3")),
					"zone_4" => clean_number($this->input->post("price_4")),
					"zone_5" => clean_number($this->input->post("price_5"))
				]);
				$data["is_price_zone"] = 1;
			}
			$this->product_model->add_product($data);
			//last id
			$last_id = $this->db->insert_id();
			//update slug
			$this->product_model->update_slug($last_id);
			//add product images
			$this->file_model->add_product_images($last_id);
			$this->db->trans_complete();
			return true;
		}
	}

	public function add_general_product_post()
	{
		$this->default_add_product_validation();
		$this->form_validation->set_rules("price", "Harga", "required|numeric|greater_than_equal_to[1]");
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('error', trans("msg_error"));
			// dd($this->form_validation->error_array());
			return false;
		} else {
			$this->db->trans_begin();
			$data = [
				"category_id" => $this->input->post("category_product"),
				"title" => $this->input->post("title"),
				"minimum_order" => $this->input->post("minimum_order"),
				"stock" => clean_number($this->input->post("stock")),
				"custom_category" => $this->input->post("custom_category"),
				"kbki" => clean_number($this->input->post("kbki")),
				"description" => html_escape($this->input->post("description")),
				"is_homemade" => filter_var($this->input->post("category_homemade"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_umkm_product" => filter_var($this->input->post("category_umkm"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_kemendikbud_product" => filter_var($this->input->post("category_kemendikbud"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"visibility" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"publisher" => $this->input->post("publisher") ?? "",
				"warranty" => $this->input->post("warranty"),
				"guarantee" => $this->input->post("delivery_assurance") ?? "", //https://lifepal.co.id/media/asuransi-pengiriman-barang/
				"product_condition" => $this->input->post("condition"),
				"width" => $this->input->post("width") ?? 0,
				"length" => $this->input->post("length") ?? 0,
				"height" => $this->input->post("height") ?? 0,
				"weight" => $this->input->post("weight") ?? 0,
				"shipping_courier_id" => $this->input->post("courier"),
				"shipping_method" => $this->input->post("delivery_method"),
				"shipping_time" => $this->input->post("delivery_time"),
				"catalog_id" => null,
				"catalog_type" => null,
				"type_product_id" => 2, //Non Buku
				"status_product_id" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 2,
				"currency" => "IDR",
				"user_id" => $this->auth_user->id,
				"availability_status" => $this->input->post("availability_status")
			];
			$data["price"] = clean_number($this->input->post("price"));
			$this->product_model->add_product($data);
			//last id
			$last_id = $this->db->insert_id();
			//update slug
			$this->product_model->update_slug($last_id);
			//add product images
			$this->file_model->add_product_images($last_id);
			$this->db->trans_complete();
			return true;
		}
	}

	public function add_service_product_post()
	{
		$this->default_add_product_validation(true);
		$this->form_validation->set_rules("price", "Harga", "required|numeric|greater_than_equal_to[1]");
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('error', trans("msg_error"));
			// dd($this->form_validation->error_array());
			return false;
		} else {
			$this->db->trans_begin();
			$data = [
				"category_id" => $this->input->post("category_product"),
				"title" => $this->input->post("title"),
				"minimum_order" => $this->input->post("minimum_order"),
				"stock" => 0,
				"custom_category" => $this->input->post("custom_category"),
				"kbki" => clean_number($this->input->post("kbki")),
				"description" => html_escape($this->input->post("description")),
				"is_homemade" => filter_var($this->input->post("category_homemade"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_umkm_product" => filter_var($this->input->post("category_umkm"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"is_kemendikbud_product" => filter_var($this->input->post("category_kemendikbud"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"visibility" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
				"publisher" => null,
				"warranty" => $this->input->post("warranty"),
				"guarantee" => $this->input->post("guarantee") ?? "", //https://lifepal.co.id/media/asuransi-pengiriman-barang/
				"product_condition" => $this->input->post("condition"),
				"width" => 0,
				"length" => 0,
				"height" => 0,
				"weight" => 0,
				"shipping_courier_id" => null,
				"shipping_method" => null,
				"shipping_time" => null,
				"catalog_id" => null,
				"catalog_type" => null,
				"type_product_id" => 3, //Jasa
				"status_product_id" => filter_var($this->input->post("visibility"), FILTER_VALIDATE_BOOLEAN) ? 1 : 2,
				"currency" => "IDR",
				"user_id" => $this->auth_user->id,
				"availability_status" => null
			];
			$data["price"] = clean_number($this->input->post("price"));
			$this->product_model->add_product($data);
			//last id
			$last_id = $this->db->insert_id();
			//update slug
			$this->product_model->update_slug($last_id);
			//add product images
			$this->file_model->add_product_images($last_id);
			$this->db->trans_complete();
			return true;
		}
	}


	/**
	 * Edit Draft
	 */
	public function edit_draft($id)
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$data["product"] = $this->product_admin_model->get_product($id);
		if (empty($data["product"])) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->is_draft != 1) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
			redirect($this->agent->referrer());
		}

		$data['title'] = trans("sell_now");
		$data['description'] = trans("sell_now") . " - " . $this->app_name;
		$data['keywords'] = trans("sell_now") . "," . $this->app_name;

		$data['category'] = $this->category_model->get_category($data["product"]->category_id);
		$data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
		$data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
		$data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();


		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product
	 */
	public function edit_product($id)
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$data["product"] = $this->product_admin_model->get_product($id);
		if (empty($data["product"])) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->is_deleted == 1) {
			if ($this->auth_user->role != "admin") {
				redirect($this->agent->referrer());
			}
		}
		if ($data["product"]->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
			redirect($this->agent->referrer());
		}

		$data['title'] = trans("edit_product");
		$data['description'] = trans("edit_product") . " - " . $this->app_name;
		$data['keywords'] = trans("edit_product") . "," . $this->app_name;

		$data['category'] = $this->category_model->get_category($data["product"]->category_id);
		$data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
		$data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
		$data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();


		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product Post
	 */
	public function edit_product_post()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		//validate inputs
		$this->form_validation->set_rules('title', trans("title"), 'required|xss_clean|max_length[500]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			//product id
			$product_id = $this->input->post('id', true);
			//user id
			$user_id = 0;
			$product = $this->product_admin_model->get_product($product_id);
			if (!empty($product)) {
				$user_id = $product->user_id;
			}
			if ($product->user_id != $this->auth_user->id && $this->auth_user->role != "admin") {
				redirect($this->agent->referrer());
			}

			if ($this->product_model->edit_product($product)) {
				//edit slug
				$this->product_model->update_slug($product_id);

				if ($product->is_draft == 1) {
					redirect(generate_url("sell_now", "product_details") . '/' . $product_id);
				} else {
					//reset cache
					reset_cache_data_on_change();
					reset_user_cache_data($user_id);
					reset_product_img_cache_data($product_id);

					$this->session->set_flashdata('success', trans("msg_updated"));
					redirect($this->agent->referrer());
				}
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Edit Product Details
	 */
	public function edit_product_details($id)
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && $this->auth_user->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(generate_url("settings", "update_profile"));
		}

		$data['product'] = $this->product_admin_model->get_product($id);
		if (empty($data['product'])) {
			redirect($this->agent->referrer());
		}
		if ($this->auth_user->role != 'admin' && $this->auth_user->id != $data['product']->user_id) {
			redirect($this->agent->referrer());
			exit();
		}

		if ($data['product']->is_draft == 1) {
			$data['title'] = trans("sell_now");
			$data['description'] = trans("sell_now") . " - " . $this->app_name;
			$data['keywords'] = trans("sell_now") . "," . $this->app_name;
		} else {
			$data['title'] = trans("edit_product");
			$data['description'] = trans("edit_product") . " - " . $this->app_name;
			$data['keywords'] = trans("edit_product") . "," . $this->app_name;
		}

		$data["custom_field_array"] = $this->field_model->generate_custom_fields_array($data["product"]->category_id, $data["product"]->id);
		$data["product_variations"] = $this->variation_model->get_product_variations($data["product"]->id);
		$data["user_variations"] = $this->variation_model->get_variation_by_user_id($data["product"]->user_id);
		$data['form_settings'] = $this->settings_model->get_form_settings();
		$data['license_keys'] = $this->product_model->get_license_keys($data["product"]->id);

		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product_details');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product Details Post
	 */
	public function edit_product_details_post()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$product_id = $this->input->post('id', true);
		$product = $this->product_admin_model->get_product($product_id);
		if (empty($product)) {
			redirect($this->agent->referrer());
			exit();
		}
		if ($this->auth_user->role != 'admin' && $this->auth_user->id != $product->user_id) {
			redirect($this->agent->referrer());
			exit();
		}

		if ($this->product_model->edit_product_details($product_id)) {
			//edit custom fields
			$this->product_model->update_product_custom_fields($product_id);

			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data($this->auth_user->id);

			if ($product->is_draft != 1) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				redirect($this->agent->referrer());
			} else {
				//send email
				if ($this->general_settings->send_email_new_product == 1) {
					$email_data = array(
						'email_type' => 'new_product',
						'product_id' => $product->id
					);
					$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
				}

				//if draft
				if ($this->input->post('submit', true) == 'save_as_draft') {
					redirect(generate_url("drafts"));
					exit();
				}
				if ($this->general_settings->promoted_products == 1) {
					redirect(generate_url("promote_product", "pricing") . "/" . $product_id . "?type=new");
				} else {
					redirect(generate_product_url($product));
				}
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Delete Product
	 */
	public function delete_product()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$id = $this->input->post('id', true);

		//user id
		$user_id = 0;
		$product = $this->product_admin_model->get_product($id);
		if (!empty($product)) {
			$user_id = $product->user_id;
		}

		if ($this->auth_user->role == "admin" || $this->auth_user->id == $user_id) {
			if ($this->product_model->delete_product($id)) {
				$this->session->set_flashdata('success', trans("msg_product_deleted"));
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
			}

			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data($user_id);
		}
	}

	/**
	 * Delete Draft
	 */
	public function delete_draft()
	{
		//check auth
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$id = $this->input->post('id', true);

		//user id
		$user_id = 0;
		$product = $this->product_admin_model->get_product($id);
		if (!empty($product)) {
			$user_id = $product->user_id;
		}

		if ($this->auth_user->role == "admin" || $this->auth_user->id == $user_id) {
			$this->product_admin_model->delete_product_permanently($id);
			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data($user_id);
		}
	}


	//add review
	public function add_review_post()
	{
		if ($this->auth_check && $this->general_settings->reviews == 1) {
			$rating = $this->input->post('rating', true);
			$product_id = $this->input->post('product_id', true);
			$review_text = $this->input->post('review', true);
			$product = $this->product_model->get_product_by_id($product_id);
			if ($product->user_id != $this->auth_user->id) {
				$review = $this->review_model->get_review($product_id, $this->auth_user->id);
				if (!empty($review)) {
					$this->review_model->update_review($review->id, $rating, $product_id, $review_text);
				} else {
					$this->review_model->add_review($rating, $product_id, $review_text);
				}
			}
		}
		redirect($this->agent->referrer());
	}

	//load more review
	public function load_more_review()
	{
		$product_id = $this->input->post('product_id', true);
		$limit = $this->input->post('limit', true);
		$new_limit = $limit + $this->review_limit;
		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["reviews"] = $this->review_model->get_limited_reviews($product_id, $new_limit);
		$data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
		$data['review_limit'] = $new_limit;

		$this->load->view('product/details/_make_review', $data);
	}

	//delete review
	public function delete_review()
	{
		$id = $this->input->post('id', true);
		$product_id = $this->input->post('product_id', true);
		$user_id = $this->input->post('user_id', true);
		$limit = $this->input->post('limit', true);

		$review = $this->review_model->get_review($product_id, $user_id);
		if ($this->auth_check && !empty($review)) {
			if ($this->auth_user->role == "admin" || $this->auth_user->id == $review->user_id) {
				$this->review_model->delete_review($id, $product_id);
			}
		}

		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
		$data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
		$data['review_limit'] = $limit;

		$this->load->view('product/details/_make_review', $data);
	}

	//show address on map
	public function show_address_on_map()
	{
		// $country_text = $this->input->post('country_text', true);
		// $country_val = $this->input->post('country_val', true);
		// $state_text = $this->input->post('state_text', true);
		// $state_val = $this->input->post('state_val', true);
		$address = $this->input->post('address', true);
		$zip_code = $this->input->post('zip_code', true);

		$adress_details = $address . " " . $zip_code;
		$data["map_address"] = "";
		if (!empty($adress_details)) {
			$data["map_address"] = $adress_details . " ";
		}
		// if (!empty($state_val)) {
		//     $data["map_address"] = $data["map_address"] . $state_text . " ";
		// }
		// if (!empty($country_val)) {
		//     $data["map_address"] = $data["map_address"] . $country_text;
		// }

		$this->load->view('product/_load_map', $data);
	}

	//get activated product system
	public function get_activated_product_system()
	{
		$array = array(
			'active_system_count' => 0,
			'active_system_value' => "",
		);
		if ($this->general_settings->marketplace_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "sell_on_site";
		}
		if ($this->general_settings->classified_ads_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "ordinary_listing";
		}
		if ($this->general_settings->bidding_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "bidding";
		}
		return $array;
	}

	public function valid_checkbox($str = null)
	{
		$this->form_validation->set_message('valid_checkbox', '{field} cek box tidak valid');
		if ((!empty($str) && $str == "on")) {
			return true;
		} else if (empty($str)) {
			return true;
		} else {
			return false;
		}
	}

	// public function get_catalog_text_books()
	// {
	// 	echo $this->book_model->get_text_books();
	// }

	/*
    *------------------------------------------------------------------------------------------
    * LICENSE KEYS
    *------------------------------------------------------------------------------------------
    */
	//add license keys
	public function add_license_keys()
	{
		post_method();
		$product_id = $this->input->post('product_id', true);
		$product = $this->product_model->get_product_by_id($product_id);

		if (!empty($product)) {
			if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
				$this->product_model->add_license_keys($product_id);
				$this->session->set_flashdata('success', trans("msg_add_license_keys"));
				$data = array(
					'result' => 1,
					'success_message' => $this->load->view('partials/_messages', '', true)
				);
				echo json_encode($data);
				reset_flash_data();
			}
		}
	}

	//delete license key
	public function delete_license_key()
	{
		post_method();
		$id = $this->input->post('id', true);
		$product_id = $this->input->post('product_id', true);
		$product = $this->product_model->get_product_by_id($product_id);
		if (!empty($product)) {
			if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
				$this->product_model->delete_license_key($id);
			}
		}
	}

	//refresh license keys list
	public function refresh_license_keys_list()
	{
		post_method();
		$product_id = $this->input->post('product_id', true);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		if (!empty($data['product'])) {
			if ($this->auth_user->id == $data['product']->user_id || $this->auth_user->role == 'admin') {
				$data['license_keys'] = $this->product_model->get_license_keys($product_id);
				$this->load->view("product/license/_license_keys_list", $data);
			}
		}
	}
}
