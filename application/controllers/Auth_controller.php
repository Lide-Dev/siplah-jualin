<?php

use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Ramsey\Uuid\Uuid;

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Login Post
	 */
	public function login_ajax_post()
	{
		//check auth
		if ($this->auth_check) {
			$data = array(
				'result' => 1
			);
			echo json_encode($data);
			exit();
		}
		//validate inputs
		$this->form_validation->set_rules('email', trans("email_address"), 'required|xss_clean|max_length[100]');
		$this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|max_length[30]');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->auth_model->input_values());
			$this->load->view('partials/_messages');
		} else {
			if ($this->auth_model->login()) {
				$data = array(
					'result' => 1
				);
				echo json_encode($data);
			} else {
				$data = array(
					'result' => 0,
					'error_message' => $this->load->view('partials/_messages', '', true)
				);
				echo json_encode($data);
			}
			reset_flash_data();
		}
	}

	public function login_post($role = null)
	{
		//check auth
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		//validate inputs
		$role = xss_clean($role);
		$this->form_validation->set_rules('email', trans("email_address"), 'required|xss_clean|max_length[100]');
		$this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|max_length[30]');
		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->auth_model->input_values());
			if ($role == "supervisor")
				redirect(supervisor_url() . "login");
			else
				redirect(base_url("login/$role"));
			// dd($role);
		} else {
			if ($this->auth_model->login($role)) {
				if ($role == "admin")
					redirect(admin_url());
				else if ($role == "supervisor")
					redirect(supervisor_url());
				else
					redirect(lang_base_url());
			} else {
				if ($role == "supervisor")
					redirect(supervisor_url() . "login");
				else
					redirect(base_url("login/$role"));
			}
			reset_flash_data();
		}
	}

	/**
	 * Handle after redirect from Dapodik SSO.
	 *
	 * @return void
	 * @author Herlandro T. <herlandrotri@gmail.com>
	 */
	public function redirect_login_satdik()
	{


		// $this->config->load("dapodik_config");
		$code = $this->input->get("code");

		// WE USING TRY & CATCH FOR TESTING PURPOSE ONLY. IF WE GOT A ERROR IT WiLL NOT RUINING ALL FUNCTION.
		try {
			// GET TOKEN FROM DAPODIK BY CODE FROM URL REDIRECT
			$client = new Client([
				"base_uri" => $this->config->item("dapodik_sso_base_url"),
			]);
			$response = $client->post("token", ["form_params" => [
				"code" => $code,
				"app_id" => $this->config->item("dapodik_app_id"),
				"client_secret" => $this->config->item("dapodik_client_secret"),
			]]);

			switch ($response->getStatusCode()) {
				case 200:
					$token = $response->getBody()->getContents();
					break;
				case 500:
					$this->session->set_tempdata("errors", "Kesalahan dari server Dapodik. Silahkan menunggu beberapa menit atau jika pernah terdaftar di Jualin maka masuk dengan emergency login.");
					redirect(base_url("login/member"));
					break;
				default:
					$this->session->set_tempdata("errors", "Laporkan ke Admin Jualin untuk lebih lanjut. Error code: 201.");
					redirect(base_url("login/member"));
					break;
			}
			// dd($token->getContents());
			$response = $client->post("profile", ["form_params" => [
				"token" => $token,
			]]);
			// dd($response,$response->getStatusCode(),"ss");
			// GET USER PROFILE SATDIK
			$user_profile = [];
			$auth_data = [];
			switch ($response->getStatusCode()) {
				case 200:
					$json = (array) json_decode($response->getBody()->getContents());
					$auth_data["id"] = $json["pengguna_id"];
					$auth_data["email"] = $json["username"];
					$user_profile["user_id"] = $json["pengguna_id"];
					$user_profile["name"] = $json["nama"];
					$user_profile["nik"] = $json["nik"];
					$user_profile["nip"] = $json["nip"];
					// $user_profile["nuptk"] = $json["ptk_id"];
					$user_profile["role_id"] = $json["peran_id"];
					$user_profile["role_name"] = $json["peran"];
					$user_profile["satdik_id"] = $json["sekolah_id"];
					$user_profile["position"] = $json["jabatan"];
					$user_profile["phone_number"] = $json["no_hp"] ?? $json["no_telepon"];
					break;
				case 500:
					$this->session->set_tempdata("errors", "Kesalahan dari server Dapodik. Silahkan menunggu beberapa menit atau jika pernah terdaftar di Jualin maka masuk dengan emergency login.");
					redirect(base_url("login/member"));
					break;
				default:
					$this->session->set_tempdata("errors", "Laporkan ke Admin Jualin untuk lebih lanjut. Error code: 201.");
					redirect(base_url("login/member"));
					break;
			}


			// GET PROFILE SCHOOL FROM DAPODIK BY TOKEN
			$response = $client->post("infosp", ["form_params" => [
				"token" => $token,
			]]);
			$school_profile = [];
			switch ($response->getStatusCode()) {
				case 200:
					$json = (array)json_decode($response->getBody()->getContents());
					// dd($json);
					$school_profile["id"] = $json["sekolah_id"];
					$school_profile["school_name"] = $json["nama_sekolah"];
					$school_profile["npsn"] = $json["npsn"];
					$school_profile["status"] = $json["status"];
					$school_profile["format"] = $json["bentuk_pendidikan"];
					$school_profile["region_id"] = $json["kd_kab"];
					$school_profile["address"] = $json["alamat"];
					$school_profile["village"] = $json["desa"];
					$school_profile["district"] = $json["kec"];
					// $school_profile["province"] = $json["prov"];
					$school_profile["zip_code"] = $json["kode_pos"];
					$school_profile["latitude"] = $json["lintang"];
					$school_profile["longitude"] = $json["bujur"];
					$school_profile["email"] = $json["email"];
					$school_profile["phone_number"] = $json["nomor_telepon"];
					break;
				case 500:
					$this->session->set_tempdata("errors", "Kesalahan dari server Dapodik. Silahkan menunggu beberapa menit atau jika pernah terdaftar di Jualin maka masuk dengan emergency login.");
					redirect(base_url("login/member"));
					break;
				default:
					$this->session->set_tempdata("errors", "Laporkan ke Admin Jualin untuk lebih lanjut. Error code: 201.");
					redirect(base_url("login/member"));
					break;
			}

			$this->satdik_model->set_satdik($school_profile);
			$this->satdik_model->set_satdik_user($user_profile, $auth_data);
			$user_data = array(
				'modesy_sess_user_id' => $auth_data["id"],
				'modesy_sess_user_email' => $auth_data["email"],
				'modesy_sess_user_role' => "member",
				'modesy_sess_logged_in' => true,
				'modesy_sess_app_key' => $this->config->item('app_key'),
			);
			$this->session->set_userdata($user_data);
			// dd($user_data,$this->session->userdata());
			redirect(lang_base_url());
			reset_flash_data();
		} catch (\Throwable $th) {
			$this->session->set_tempdata("errors", "Laporkan ke Admin Jualin untuk lebih lanjut. Error code: 202.");
			redirect(base_url("login/member"));
		}
	}

	/**
	 * Connect with Facebook
	 */
	public function connect_with_facebook()
	{
		$fb_url = "https://www.facebook.com/v3.3/dialog/oauth?client_id=" . $this->general_settings->facebook_app_id . "&redirect_uri=" . base_url() . "facebook-callback&scope=email&state=" . generate_unique_id();

		$this->session->set_userdata('fb_login_referrer', $this->agent->referrer());
		redirect($fb_url);
		exit();
	}

	/**
	 * Facebook Callback
	 */
	public function facebook_callback()
	{
		require_once APPPATH . "third_party/facebook/vendor/autoload.php";

		$fb = new \Facebook\Facebook([
			'app_id' => $this->general_settings->facebook_app_id,
			'app_secret' => $this->general_settings->facebook_app_secret,
			'default_graph_version' => 'v2.10',
		]);
		try {
			$helper = $fb->getRedirectLoginHelper();
			$permissions = ['email'];
			if (isset($_GET['state'])) {
				$helper->getPersistentDataHandler()->set('state', $_GET['state']);
			}
			$accessToken = $helper->getAccessToken();
			$response = $fb->get('/me?fields=name,email', $accessToken);
		} catch (\Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch (\Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$user = $response->getGraphUser();
		$fb_user = new stdClass();
		$fb_user->id = $user->getId();
		$fb_user->email = $user->getEmail();
		$fb_user->name = $user->getName();

		$this->auth_model->login_with_facebook($fb_user);

		if (!empty($this->session->userdata('fb_login_referrer'))) {
			redirect($this->session->userdata('fb_login_referrer'));
		} else {
			redirect(base_url());
		}
	}

	/**
	 * Connect with Google
	 */
	public function connect_with_google()
	{
		require_once APPPATH . "third_party/google/vendor/autoload.php";

		$provider = new League\OAuth2\Client\Provider\Google([
			'clientId' => $this->general_settings->google_client_id,
			'clientSecret' => $this->general_settings->google_client_secret,
			'redirectUri' => base_url() . 'connect-with-google',
		]);

		if (!empty($_GET['error'])) {
			// Got an error, probably user denied access
			exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));
		} elseif (empty($_GET['code'])) {

			// If we don't have an authorization code then get one
			$authUrl = $provider->getAuthorizationUrl();
			$_SESSION['oauth2state'] = $provider->getState();
			$this->session->set_userdata('g_login_referrer', $this->agent->referrer());
			header('Location: ' . $authUrl);
			exit();
		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
			// State is invalid, possible CSRF attack in progress
			unset($_SESSION['oauth2state']);
			exit('Invalid state');
		} else {
			// Try to get an access token (using the authorization code grant)
			$token = $provider->getAccessToken('authorization_code', [
				'code' => $_GET['code']
			]);
			// Optional: Now you have a token you can look up a users profile data
			try {
				// We got an access token, let's now get the owner details
				$user = $provider->getResourceOwner($token);

				$g_user = new stdClass();
				$g_user->id = $user->getId();
				$g_user->email = $user->getEmail();
				$g_user->name = $user->getName();
				$g_user->avatar = $user->getAvatar();

				$this->auth_model->login_with_google($g_user);

				if (!empty($this->session->userdata('g_login_referrer'))) {
					redirect($this->session->userdata('g_login_referrer'));
				} else {
					redirect(base_url());
				}
			} catch (Exception $e) {
				// Failed to get user details
				exit('Something went wrong: ' . $e->getMessage());
			}
		}
	}

	/**
	 * Connect with VK
	 */
	public function connect_with_vk()
	{
		require_once APPPATH . "third_party/vkontakte/vendor/autoload.php";
		$provider = new J4k\OAuth2\Client\Provider\Vkontakte([
			'clientId' => $this->general_settings->vk_app_id,
			'clientSecret' => $this->general_settings->vk_secure_key,
			'redirectUri' => base_url() . 'connect-with-vk',
			'scopes' => ['email'],
		]);
		// Authorize if needed
		if (PHP_SESSION_NONE === session_status()) session_start();
		$isSessionActive = PHP_SESSION_ACTIVE === session_status();
		$code = !empty($_GET['code']) ? $_GET['code'] : null;
		$state = !empty($_GET['state']) ? $_GET['state'] : null;
		$sessionState = 'oauth2state';

		// No code – get some
		if (!$code) {
			$authUrl = $provider->getAuthorizationUrl();
			if ($isSessionActive) $_SESSION[$sessionState] = $provider->getState();
			$this->session->set_userdata('vk_login_referrer', $this->agent->referrer());
			header("Location: $authUrl");
			die();
		} // Anti-CSRF
		elseif ($isSessionActive && (empty($state) || ($state !== $_SESSION[$sessionState]))) {
			unset($_SESSION[$sessionState]);
			throw new \RuntimeException('Invalid state');
		} // Exchange code to access_token
		else {
			try {
				$providerAccessToken = $provider->getAccessToken('authorization_code', ['code' => $code]);

				$user = $providerAccessToken->getValues();
				//get user details with cURL
				$url = 'http://api.vk.com/method/users.get?uids=' . $providerAccessToken->getValues()['user_id'] . '&access_token=' . $providerAccessToken->getToken() . '&v=5.95&fields=photo_200,status';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$response = curl_exec($ch);
				curl_close($ch);
				$user_details = json_decode($response);

				$vk_user = new stdClass();
				$vk_user->id = $providerAccessToken->getValues()['user_id'];
				$vk_user->email = $providerAccessToken->getValues()['email'];
				$vk_user->name = @$user_details->response['0']->first_name . " " . @$user_details->response['0']->last_name;
				$vk_user->avatar = @$user_details->response['0']->photo_200;

				$this->auth_model->login_with_vk($vk_user);

				if (!empty($this->session->userdata('vk_login_referrer'))) {
					redirect($this->session->userdata('vk_login_referrer'));
				} else {
					redirect(base_url());
				}
			} catch (IdentityProviderException $e) {
				// Log error
				error_log($e->getMessage());
			}
		}
	}

	/**
	 * Admin Login
	 */
	public function admin_login()
	{
		if ($this->auth_check) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("login");
		$data['description'] = trans("login") . " - " . $this->settings->site_title;
		$data['keywords'] = trans("login") . ', ' . $this->general_settings->application_name;
		$this->load->view('admin/login', $data);
	}

	/**
	 * Admin Login Post
	 */
	public function admin_login_post()
	{
		//validate inputs
		$this->form_validation->set_rules('email', trans("form_email"), 'required|xss_clean|max_length[200]');
		$this->form_validation->set_rules('password', trans("form_password"), 'required|xss_clean|max_length[30]');

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->auth_model->input_values());
			redirect($this->agent->referrer());
		} else {
			if ($this->auth_model->login()) {
				redirect(admin_url());
			} else {
				//error
				$this->session->set_flashdata('form_data', $this->auth_model->input_values());
				$this->session->set_flashdata('error', trans("login_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Register
	 */
	public function register_seller()
	{
		//check if logged in
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("register_seller");
		$data['description'] = trans("register_seller") . " - " . $this->app_name;
		$data['keywords'] = trans("register_seller") . "," . $this->app_name;

		$data["provinces"] = $this->location_model->get_province();
		$data["cities"] = empty(set_value("city")) ? [] : $this->location_model->get_city(set_value("province"));
		$data["banks"] = $this->bank_model->get_bank();
		// dd($data);
		$this->load->view('partials/_header', $data);
		$this->load->view('auth/register_seller', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Register Buyer
	 */
	public function register_buyer()
	{
		//check if logged in
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("register_buyer");
		$data['description'] = trans("register_buyer") . " - " . $this->app_name;
		$data['keywords'] = trans("register_buyer") . "," . $this->app_name;

		$this->load->view('partials/_header', $data);
		$this->load->view('auth/register_buyer');
		$this->load->view('partials/_footer');
	}

	/**
	 * Register the user with role supplier.
	 *
	 * @param mixed $data
	 * Prefer to be array.
	 * @param string $role
	 * Can be used with constant ROLE_ADMIN, any other ROLE_...
	 * @return void
	 * @author Herlandro T <herlandrotri@gmail.com>
	 */
	public function register_supplier_post()
	{
		$this->load->helper('file');

		$this->form_validation->set_rules("business_profile", "Profil Bisnis", "required|in_list[business_entity,individual]");
		$this->form_validation->set_rules("legal_status", "Status Wajib Pajak", "required|in_list[pkp,individual,non_pkp]");
		$this->form_validation->set_rules("business_type", "Tipe Bisnis", "required|in_list[micro,small,medium,non_umkm]");
		$this->form_validation->set_rules("business_name", trans("business_name"), "required|is_unique[supplier_profiles.supplier_name]|max_length[254]");
		$this->form_validation->set_rules("npwp", "NPWP", "required|numeric|exact_length[15]|is_unique[supplier_profiles.npwp]");
		$this->form_validation->set_rules('npwp_document', 'Dokumen NPWP', 'callback_file_check[npwp_document]');
		$this->form_validation->set_rules('business_support_document', 'Dokumen Pendukung', 'required|in_list[siup,nib,tdp]');
		// $this->form_validation->set_rules("umkm", trans("tax_status"), "required|in_list[umkm,non_umkm]");
		$this->form_validation->set_rules("address", trans("address"), "required|max_length[254]");
		$this->form_validation->set_rules("province", trans("province"), ["required", ["callback_valid_province", [$this->location_model, "valid_province"]]]);
		$this->form_validation->set_rules("city", trans("city"), ["required", ["callback_valid_city", [$this->location_model, "valid_city"]]]);
		$this->form_validation->set_rules("district", trans("district"), "required|max_length[254]");
		$this->form_validation->set_rules("village", trans("village"), "required|max_length[254]");
		$this->form_validation->set_rules("postal_code", trans("postal_code"), "required|max_length[254]");
		$this->form_validation->set_rules("bank", "Bank", ["required", ["callback_valid_bank", [$this->bank_model, "valid_bank"]]]);
		$this->form_validation->set_rules("account_number", trans("account_number"), "required|numeric|max_length[25]|numeric");
		$this->form_validation->set_rules("bank_account_holder", trans("bank_account_holder"), "required|max_length[254]");
		$this->form_validation->set_rules('cover_book_document', 'Dokumen Buku Tabungan', 'callback_file_check[cover_book_document]');
		// $this->form_validation->set_rules("full_name", trans("full_name"), "required|max_length[254]");
		// $this->form_validation->set_rules("position", trans("position"), "required|max_length[254]");
		$this->form_validation->set_rules("email_address", trans("email_address"), "required|is_unique[users.email]|valid_email");
		$this->form_validation->set_rules("password", trans("password"), "required|min_length[8]|max_length[60]");
		$this->form_validation->set_rules("confirm_password", "Konfirmasi Password", "required|min_length[8]|max_length[60]|matches[password]");
		$this->form_validation->set_rules("phone_number", trans("phone_number"), ["required", "min_length[8]", "max_length[20]" . "numeric", ["callback_valid_phone_number", [$this->auth_model, "valid_phone_number"]]]);
		if ($this->input->post("business_profile") == "individual") {
			$this->form_validation->set_rules("nik", "NIK", "required|exact_length[16]");
		} else {
			$this->form_validation->set_rules("responsible_person_name", trans("full_name") . " Penanggung Jawab", "required|max_length[254]");
			$this->form_validation->set_rules("responsible_person_position", trans("position") . " Penanggung Jawab", "required|max_length[254]");
			if ($this->input->post("business_support_document") == "siup") {
				if (!empty($_FILES["siup_document"])) {
					$this->form_validation->set_rules('siup_document', 'Dokumen SIUP', 'callback_file_check[siup_document]');
				}
			} elseif ($this->input->post("business_support_document") == "nib") {
				if (!empty($_FILES["nib_document"])) {
					$this->form_validation->set_rules('nib_document', 'Dokumen NIB', 'callback_file_check[nib_document]');
				}
				if (empty($this->input->post("nib"))) {
					$this->form_validation->set_rules('nib', 'NIB', 'required|max_length[13]');
				}
			} elseif ($this->input->post("business_support_document") == "tdp") {
				if (!empty($_FILES["tdp_document"])) {
					$this->form_validation->set_rules('tdp_document', 'Dokumen TDP', 'callback_file_check[tdp_document]');
				}
			}
		}
		$this->form_validation->set_error_delimiters("<small class='text-danger'>", "</small>");
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata("status_message", "<div class='alert alert-danger' role='alert'>Gagal Registrasi! Silahkan mengecek kembali form data yang masih kurang lengkap.</div>");
			// $this->session->set_flashdata("")
			// $this->session->keep_flashdata("status_message");
			// dd($_SESSION,$this->form_validation->error_array());                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 or_array());
			// dd($this->form_validation->error_array());
			$this->register_seller();
			// redirect(base_url("register_seller"));
		} else {
			$user_data = [
				"user" => [
					"email" => $this->input->post("email_address"),
					"password" => $this->input->post("password"),
					"role" => "supplier"
				],
				"profile" => [
					"supplier_name" => $this->input->post("business_name"),
					"npwp" => $this->input->post("npwp"),
					"nib" => $this->input->post("nib"),
					"phone_number" => $this->input->post("phone_number"),
					"is_business_entity" => $this->input->post("business_profile") == "business_entity" ? 1 : 0,
					"business_type_id" => $this->input->post("business_type"),
					"legal_status_id" => $this->input->post("legal_status"),
					"bank_id" => $this->input->post("bank"),
					"bank_account" => $this->input->post("account_number"),
					"bank_account_owner_name" => $this->input->post("bank_account_holder"),
					"address" => $this->input->post("address"),
					"city_id" => $this->input->post("city"),
					"district" => $this->input->post("district"),
					"village" => $this->input->post("village"),
					"zip_code" => $this->input->post("postal_code"),
				]
			];
			if ($this->input->post("business_profile") == "individual") {
				$user_data["profile"]["nik"] = $this->input->post("nik");
			} else {
				$user_data["profile"]["responsible_person_name"] = $this->input->post("responsible_person_name");
				$user_data["profile"]["responsible_person_position"] = $this->input->post("responsible_person_position");
			}
			$file_data = [
				"npwp" => $_FILES["npwp_document"],
				"siup" => $_FILES["siup_document"] ?? null,
				"nib" => $_FILES["nib_document"] ?? null,
				"tdp" => $_FILES["tdp_document"] ?? null,
				"cover_book" => $_FILES["cover_book_document"],
				"ktp" => $_FILES["ktp_document"] ?? null
			];
			$transact = $this->auth_model->register_supplier($user_data, $file_data) ? "TRUE" : "FALSE";

			// $this->session->set_flashdata("status_message", "<div class='alert alert-danger' role='alert'>Gagal Registrasi! (SERVER) Silahkan mengecek kembali form data yang masih kurang lengkap.</div>");
			// $this->load->view('partials/_header', $data);
			// $this->load->view('auth/register_seller', $data);
			// $this->load->view('partials/_footer');
			// dd("Validation_complete, Transact fail");

			// dd("Validation_complete, Transact complete");

			$this->session->set_flashdata("status_message", "<div class='alert alert-primary' role='alert'>Berhasil Registrasi! ($transact) Silahkan verifikasi email dan menunggu konfirmasi toko anda telah dibuka.</div>");
			// $this->session->keep_flashdata("status_message");
			$this->register_seller();
			// redirect(base_url("register_seller"));
		}
	}

	/**
	 * Validate file document on register
	 *
	 * @param [type] $str
	 * @return void
	 */
	public function file_check($str, $field)
	{
		$this->load->helper('number');
		$allowed_max_byte = 2097152;
		$allowed_mime_type_arr = array('application/pdf', 'image/jpeg', 'image/jpg', 'image/png');
		$mime = get_mime_by_extension($_FILES[$field]['name']);
		if (isset($_FILES[$field]['name']) && $_FILES[$field]['name'] != "") {
			if (!in_array($mime, $allowed_mime_type_arr)) {
				$this->form_validation->set_message('file_check', 'Hanya ekstensi pdf/jpg/png file {field} yang diterima.');
				return false;
			}
			$this->form_validation->set_message('file_check', 'Pilih file {field} untuk di upload.');
			return false;
		}
		if ($_FILES[$field]["size"] > $allowed_max_byte) {
			$this->form_validation->set_message('file_check', 'Ukuran file {field} melebihi batas maksimal. Batas maksimal ' . byte_format($allowed_max_byte));
			return false;
		}

		return true;
	}


	/**
	 * Logout
	 */
	public function logout()
	{
		$this->auth_model->logout();
		redirect($this->agent->referrer());
	}

	/**
	 * Confirm Email
	 */
	public function confirm_email()
	{
		$data['title'] = trans("confirm_your_account");
		$data['description'] = trans("confirm_your_account") . " - " . $this->app_name;
		$data['keywords'] = trans("confirm_your_account") . "," . $this->app_name;

		$token = trim($this->input->get("token", true));
		$data["user"] = $this->auth_model->get_user_by_token($token);

		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		if ($data["user"]->email_status == 1) {
			redirect(lang_base_url());
		}

		if ($this->auth_model->verify_email($data["user"])) {
			$data["success"] = trans("msg_confirmed");
		} else {
			$data["error"] = trans("msg_error");
		}
		$this->load->view('partials/_header', $data);
		$this->load->view('auth/confirm_email', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Forgot Password
	 */
	public function forgot_password()
	{
		//check if logged in
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("reset_password");
		$data['description'] = trans("reset_password") . " - " . $this->app_name;
		$data['keywords'] = trans("reset_password") . "," . $this->app_name;

		$this->load->view('partials/_header', $data);
		$this->load->view('auth/forgot_password');
		$this->load->view('partials/_footer');
	}

	/**
	 * Forgot Password Post
	 */
	public function forgot_password_post()
	{
		//check auth
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		$email = $this->input->post('email', true);
		//get user
		$user = $this->auth_model->get_user_by_email($email);

		//if user not exists
		if (empty($user)) {
			$this->session->set_flashdata('error', html_escape(trans("msg_reset_password_error")));
			redirect($this->agent->referrer());
		} else {
			$this->load->model("email_model");
			$this->email_model->send_email_reset_password($user->id);
			$this->session->set_flashdata('success', trans("msg_reset_password_success"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Reset Password
	 */
	public function reset_password()
	{
		//check if logged in
		if ($this->auth_check) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("reset_password");
		$data['description'] = trans("reset_password") . " - " . $this->app_name;
		$data['keywords'] = trans("reset_password") . "," . $this->app_name;
		$token = $this->input->get('token', true);
		//get user
		$data["user"] = $this->auth_model->get_user_by_token($token);
		$data["success"] = $this->session->flashdata('success');

		if (empty($data["user"]) && empty($data["success"])) {
			redirect(lang_base_url());
		}

		$this->load->view('partials/_header', $data);
		$this->load->view('auth/reset_password');
		$this->load->view('partials/_footer');
	}

	/**
	 * Reset Password Post
	 */
	public function reset_password_post()
	{
		$success = $this->input->post('success', true);
		if ($success == 1) {
			redirect(lang_base_url());
		}
		// SUBSTITUTE( SUBSTITUTE ( SUBSTITUTE( LOWER(C20) ;" ";"-") ;"&";"%26") ;"/";"%47")
		$this->form_validation->set_rules('password', trans("new_password"), 'required|xss_clean|min_length[4]|max_length[50]');
		$this->form_validation->set_rules('password_confirm', trans("password_confirm"), 'required|xss_clean|matches[password]');

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->profile_model->change_password_input_values());
			redirect($this->agent->referrer());
		} else {
			$user_id = $this->input->post('id', true);
			if ($this->auth_model->reset_password($user_id)) {
				$this->session->set_flashdata('success', trans("msg_change_password_success"));
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_change_password_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Send Activation Email
	 */
	public function send_activation_email_post()
	{
		post_method();
		$user_id = $this->input->post('id', true);
		$token = $this->input->post('token', true);
		$type = $this->input->post('type', true);
		if ($type == 'login') {
			$this->session->set_flashdata('success', trans("msg_send_confirmation_email") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email('" . $user_id . "','" . $token . "');\">" . trans("resend_activation_email") . "</a>");
		} else {
			$this->session->set_flashdata('success', trans("msg_send_confirmation_email") . "&nbsp;<a href='javascript:void(0)' class='link-resend-activation-email' onclick=\"send_activation_email_register('" . $user_id . "','" . $token . "');\">" . trans("resend_activation_email") . "</a>");
		}

		$data = array(
			'result' => 1,
			'success_message' => $this->load->view('partials/_messages', '', true)
		);
		echo json_encode($data);
		reset_flash_data();
		$this->auth_model->send_email_activation($user_id, $token);
	}
}
