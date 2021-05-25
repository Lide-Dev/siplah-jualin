<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="auth-container">
			<div class="auth-box">
				<div class="row">
					<div class="col-12">
						<h1 class="title"><?php echo trans("register_seller"); ?></h1>

						<?= $_SESSION["status_message"] ?? "" ?>

						<?= $_SESSION["success"] ?? "" ?>

						<!-- form start -->
						<?php
						// if ($recaptcha_status) {

						echo form_open_multipart('register_seller', [
							'id' => 'form_validate', 'class' => 'validate_terms',
							// 'onsubmit' => "var serializedData = $(this).serializeArray();var recaptcha = ''; $.each(serializedData, function (i, field) { if (field.name == 'g-recaptcha-response') {recaptcha = field.value;}});if (recaptcha.length < 5) { $('.g-recaptcha>div').addClass('is-invalid');return false;} else { $('.g-recaptcha>div').removeClass('is-invalid');}"
						]);
						// } else {
						// echo form_open('register-post', ['id' => 'form_validate', 'class' => 'validate_terms']);
						// }
						?>
						<?= validation_errors() ?? "" ?>

						<div class="social-login-cnt">
							<?php $this->load->view("partials/_social_login", ['or_text' => trans("register_with_email")]); ?>
						</div>
						<!-- include message block -->
						<div id="result-register">
							<?php $this->load->view('partials/_messages'); ?>
						</div>
						<div class="spinner display-none spinner-activation-register">
							<div class="bounce1"></div>
							<div class="bounce2"></div>
							<div class="bounce3"></div>
						</div>
						<!-- PPROFILE business -->
						<h4 class="title-auth">1. <?php echo trans("profile_business"); ?></h4>
						<div class="row">
							<div class="col-12">
								<div class="form-group mt-2">
									<label class="radiobut" for="pb_radio_badan_usaha"> Badan Usaha (PT/CV)
										<input value="business_entity" id="pb_radio_badan_usaha" type="radio" <?= (empty(set_value("business_format")) || set_value("business_format") == "business_entity") ? "checked" : "" ?> name="business_format">
										<span class="radiomark"></span>
									</label>
									<label for="pb_radio_individu" class="radiobut">Individu / Perorangan
										<input value="individual" id="pb_radio_individu" type="radio" <?= (set_value("business_format") == "individual") ? "checked" : "" ?> name="business_format">
										<span class="radiomark"></span>
									</label>
									<?php echo form_error('business_format'); ?>
								</div>
								<!-- Status Legal -->
								<div class="form-group">
									<label class="control-label font-600 mt-3" for="form_legal_status">Jenis Usaha</label>
									<label for="ju_radio_individu" class="radiobut">Individu
										<input id="ju_radio_individu" type="radio" <?= (empty(set_value("legal_status")) || set_value("legal_status") == "individual") ? "checked" : "" ?> name="legal_status" value="individual">
										<span class="radiomark"></span>
									</label>
									<label for="ju_radio_pkp" class="radiobut">PKP
										<input id="ju_radio_pkp" type="radio" <?= (set_value("legal_status") == "pkp") ? "checked" : "" ?> name="legal_status" value="pkp">
										<span class="radiomark"></span>
									</label>
									<label for="ju_radio_non_pkp" class="radiobut">Non PKP
										<input id="ju_radio_non_pkp" type="radio" <?= (set_value("legal_status") == "non_pkp") ? "checked" : "" ?> name="legal_status" value="non_pkp">
										<span class="radiomark"></span>
									</label>
									<?php echo form_error('legal_status'); ?>
								</div>
								<!-- End of Status Legal -->
								<!-- Tipe Usaha -->
								<div class="form-group">
									<label class="control-label font-600 mt-3" for="form_business_type">Tipe Usaha</label>
									<label for="tu_radio_non_umkm" class="radiobut mb-3">Non UMKM
										<input id="tu_radio_non_umkm" type="radio" <?= (empty(set_value("business_type")) || set_value("business_type") == "non_umkm") ? "checked" : "" ?> name="business_type" value="non_umkm">
										<span class="radiomark"></span>
									</label>
									<label for="tu_radio_mikro" class="radiobut">Mikro <p class="small_reg"> (Kekayaan bersih maksimal 50 juta — tidak termasuk tanah & bangunan tempat usaha — , atau penghasilan maksimal 300 juta/ tahun)</p>
										<input id="tu_radio_mikro" type="radio" <?= (empty(set_value("business_type")) || set_value("business_type") == "micro") ? "checked" : "" ?> name="business_type" value="micro">
										<span class="radiomark"></span>
									</label>
									<label for="tu_radio_kecil" class="radiobut">Kecil <p class="small_reg"> (Kekayaan bersih 50 juta - 500 juta—tidak termasuk tanah & bangunan tempat usaha — , atau penghasilan 300 juta - 2,5 miliar/ tahun)</p>
										<input id="tu_radio_kecil" type="radio" <?= (empty(set_value("business_type")) || set_value("business_type") == "small") ? "checked" : "" ?> name="business_type" value="small">
										<span class="radiomark"></span>
									</label>
									<label for="tu_radio_menengah" class="radiobut">Menengah <p class="small_reg"> (Kekayaan bersih 500 juta - 10 miliar —tidak termasuk tanah & bangunan tempat usaha — , atau penghasilan 2,5 miliar- 50 miliar/ tahun)</p>
										<input id="tu_radio_menengah" type="radio" <?= (empty(set_value("business_type")) || set_value("business_type") == "medium") ? "checked" : "" ?> name="business_type" value="medium">
										<span class="radiomark"></span>
									</label>
									<?php echo form_error('business_type'); ?>
								</div>
								<!-- End of Tipe usaha -->

								<div class="form-group">
									<label class="control-label font-600 mt-3" for="form_business_type">Nama Usaha</label>
									<input type="text" name="business_name" class="form-control auth-form-input" placeholder="<?php echo trans("business_name"); ?>" value="<?php echo set_value("business_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
									<?php echo form_error('business_name'); ?>
								</div>

								<div class="form-group">
									<label class="control-label font-600 mt-3" for="form_business_type">Nomor NPWP</label>
									<input type="text" name="npwp" class="form-control auth-form-input" maxlength="15" pattern="\d*" placeholder="<?php echo trans("npwp"); ?>" value="<?php echo set_value("npwp"); ?>" required>
									<?php echo form_error('npwp'); ?>
									<p class="small_reg"> Masukan nomor NPWP sejumlah 15 Angka</p>
								</div>
								<!-- END OF PROFILE business -->

								<!-- UPLOAD DOC NPWP -->

								<div class="m-b-30 form_group">
									<label class="control-label font-600"><?php echo trans("upload_npwp"); ?></label>
									<input type="file" class="form-control auth-form-input" name="npwp_document" id="form_npwp_document">
									<?php echo form_error('npwp_document'); ?>
									<p class="small_reg"> Format file .png, .jpg, .jpeg atau .pdf, maksimum ukuran 1 MB</p>
								</div>
							</div>
						</div>
						<!-- END OF UPLOAD DOC NPWP -->
						<!-- NIB -->
						<div class="form-group">
							<label class="control-label font-600" for="form_legal_status">Nomor NIB</label>
							<input type="text" name="nib" class="form-control auth-form-input" maxlength="13" pattern="\d*" placeholder="<?php echo trans("nib"); ?>" value="<?php echo set_value("nib"); ?>" required>
							<?php echo form_error('nib'); ?>
							<p class="small_reg"> Masukan nomor NIB sejumlah 13 Angka</p>
						</div>

						<div class="form-group">
							<label class="control-label font-600"><?php echo "Unggah Dokumen NIB" ?></label>
							<input type="file" class="form-control auth-form-input" name="nib_document" id="form_selected_document">
							<?php echo form_error('nib_document'); ?>
							<p class="small_reg"> Format file .png, .jpg, .jpeg atau .pdf, maksimum ukuran 1 MB</p>
						</div>

						<!-- END OF NIB -->
						<!-- ADDRESS -->
						<div class="form-group">
							<label class="control-label font-600 mt-3" for="form_business_type">Alamat Lengkap</label>
							<textarea type="text" name="address" class="form-control auth-form-input" placeholder="<?php echo trans("address"); ?>" required><?php echo set_value("address"); ?></textarea>
							<?php echo form_error('address'); ?>
						</div>
						<div class="form-group">
							<select id="form_province" name="province" class="form-control auth-form-input" onchange="select_province()">
								<option value="0"> Pilih salah satu provinsi</option>
								<?php foreach ($provinces as $province) : ?>
									<option value="<?= $province->id ?>" <?= set_value("province") == $province->id ? "selected" : "" ?>> <?= $province->province_name ?></option>
								<?php endforeach ?>
							</select>
							<?php echo form_error('province'); ?>
						</div>
						<div class="form-group">
							<?php if (!empty(set_value("province"))) : ?>
								<select id="form_city" name="city" class="form-control auth-form-input" onchange="select_city()" disabled>
									<option value="0"> Kota dipilih setelah pilih provinsi terlebih dahulu </option>
								</select>
								<?php echo form_error('city'); ?>
							<?php else : ?>
								<select id="form_city" name="city" class="form-control auth-form-input" onchange="select_city()" disabled>
									<option value="0"> Kota dipilih setelah pilih provinsi terlebih dahulu </option>
									<?php foreach ($cities as $city) : ?>
										<option value="<?= $city->id ?>" <?= set_value("city") == $city->id ? "selected" : "" ?>> <?= $city->city_name ?></option>
									<?php endforeach ?>
								</select>
								<?php echo form_error('city'); ?>
							<?php endif ?>
						</div>
						<div class="form-group">
							<input type="text" id="form_district" onchange="change_district()" name="district" class="form-control auth-form-input" placeholder="<?php echo trans("district"); ?>" value="<?php echo set_value("district"); ?>" required>
							<?php echo form_error('district'); ?>
						</div>
						<div class="form-group">
							<input type="text" name="village" class="form-control auth-form-input" placeholder="<?php echo trans("village"); ?>" value="<?php echo set_value("village"); ?>" required>
							<?php echo form_error('village'); ?>
						</div>
						<div class="form-group">
							<input type="text" id="form_postal_code" onchange="change_postal_code()" name="postal_code" class="form-control auth-form-input" minlength="5" maxlength="5" placeholder="<?php echo trans("postal_code"); ?>" value="<?php echo set_value("postal_code"); ?>" required>
							<?php echo form_error('postal_code'); ?>
						</div>
						<div class="form-group m-b-30">
							<label class="control-label font-600"><?php echo "Pilih Titik Lokasi" ?></label>
							<div id="map-result">
								<div class="map-container">
									<iframe src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true" id="IframeMap" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
								</div>
							</div>
						</div>
						<!-- END OF COMPLETE ADDRESS -->

						<!-- BANK ACCOUNT -->
						<h4 class="title-auth">2. <?php echo trans("bank_title"); ?></h4>
						<div class="form-group">
							<select id="form_bank" name="bank" class="form-control auth-form-input">
								<option value="0"> Pilih salah satu bank</option>
								<?php foreach ($banks as $bank) : ?>
									<option value="<?= $bank->id ?>" <?= set_value("bank") == $bank->id ? "selected" : "" ?>> <?= $bank->bank_name ?></option>
								<?php endforeach ?>
							</select>
							<?php echo form_error('bank'); ?>
						</div>
						<div class="form-group">
							<input type="text" name="account_number" class="form-control auth-form-input" placeholder="<?php echo trans("account_number"); ?>" value="<?php echo set_value("account_number"); ?>" required>
							<?php echo form_error('account_number'); ?>
						</div>
						<div class="form-group">
							<input type="text" name="bank_account_holder" class="form-control auth-form-input" placeholder="<?php echo trans("bank_account_holder"); ?>" value="<?php echo set_value("bank_account_holder"); ?>" required>
							<?= form_error('bank_account_holder'); ?>
						</div>
						<div class="m-b-30 form_group pb-3">
							<label class="control-label font-600">Upload Foto Buku Tabungan</label>
							<input type="file" class="form-control auth-form-input" name="cover_book" id="form_cover_book">
							<?php echo form_error('cover_book'); ?>
							<p class="small_reg"> Format file .png, .jpg, .jpeg atau .pdf, maksimum ukuran 1 MB</p>
						</div>
						<!-- END OF BANK ACCOUNT -->
						<!-- RESPONSIBLE PERSON -->
						<?php if (set_value("business_format") == "business_entity" || empty(set_value("business_format"))) : ?>
							<h4 class="title-auth">3. <?php echo trans("responsible_title"); ?></h4>
							<div id="form_container_responsible_person">
								<div class="form-group">
									<input type="text" name="responsible_person_name" class="form-control auth-form-input" placeholder="<?php echo trans("full_name"); ?>" value="<?php echo set_value("responsible_person_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
									<?php echo form_error('responsible_person_name'); ?>
								</div>
								<div class="form-group">
									<input type="text" name="responsible_person_position" class="form-control auth-form-input" placeholder="<?php echo trans("user_position"); ?>" value="<?php echo set_value("responsible_person_position"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
									<?php echo form_error('responsible_person_position'); ?>
								</div>
								<div class="m-b-30 form-group">
									<label class="control-label font-600">Upload Surat Ijin Usaha Perdagangan (Opsional)</label>
									<input type="file" class="form-control auth-form-input" name="siup_document" id="form_selected_document">
									<?php echo form_error('siup_document'); ?>
									<p class="small_reg"> Format file .png, .jpg, .jpeg atau .pdf, maksimum ukuran 1 MB</p>
								</div>
							</div>
						<?php else : ?>
							<h4 class="title-auth">3. <?php echo trans("responsible_title"); ?></h4>
							<div class="form-group">
								<input type="text" name="nik" class="form-control auth-form-input" maxlength="16" pattern="\d*" placeholder="<?php echo trans("nik"); ?>" value="<?php echo set_value("nik"); ?>" required>
							</div>
						<?php endif; ?>
						<h4 class="title-auth">4. <?php echo trans("create_user"); ?></h4>
						<div class="form-group">
							<input type="email" name="email_address" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo set_value("email_address"); ?>" required>
							<?php echo form_error('email_address'); ?>
						</div>
						<div class="form-group">
							<input type="number" name="phone_number" class="form-control auth-form-input" placeholder="<?php echo trans("phone_number"); ?>" value="<?php echo set_value("phone_number"); ?>" required>
							<?php echo form_error('phone_number'); ?>
						</div>

						<label class="control-label font-600 mt-3" for="form_legal_status">Buat Password</label>
						<p class="small_reg"> Buat password anda dengan atau harus setidaknya 8 sampai 60 panjang karakter</p>
						<div class="form-group">
							<input type="password" name="password" id="password" class="form-control auth-form-input" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Harus berisi setidaknya satu angka dan satu huruf besar dan kecil, dan setidaknya 8 karakter atau lebih" placeholder="<?php echo trans("password"); ?>" required>
							<?php echo form_error('password'); ?>
						</div>

						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control auth-form-input" placeholder="<?php echo trans("password_confirm"); ?>" required>
							<?php echo form_error('confirm_password'); ?>
						</div>

						<div id="pesan">
							<p class="control-label">Kata sandi harus berisi yang berikut ini:</p>
							<p id="huruf_kecil" class="invalid"><b>huruf kecil</b></p>
							<p id="huruf_besar" class="invalid"><b>huruf besar</b></p>
							<p id="angka" class="invalid"><b>angka</b></p>
							<p id="panjang" class="invalid">Minimum <b>8 karakter</b></p>
						</div>
						<!-- END OF RESPONSIBLE PERSON -->
						<div class="form-group m-t-30 m-b-20">
							<div class="custom-control custom-checkbox custom-control-validate-input">
								<input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
								<?php $page_terms_condition = get_page_by_default_name("terms_conditions", $this->selected_lang->id); ?>
								<label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url() . $page_terms_condition->slug; ?>" class="link-terms" target="_blank"><strong><?php echo html_escape($page_terms_condition->title); ?></strong></a></label>
							</div>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("register"); ?></button>
						</div>
						<p class="p-social-media m-0 m-t-15"><?php echo trans("have_account"); ?>&nbsp;<a href="javascript:void(0)" class="link" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></p>

						<?php echo form_close(); ?>
						<!-- form end -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var full_address = {
		province: "",
		city: "",
		district: "",
		postal_code: ""
	};

	function select_province() {
		let id = $("#form_province option:selected").val();
		set_address("province", $("#form_province option:selected").html());
		full_address.city = "";
		$.get("<?= base_url() ?>get-city-option?province_id=" + id).done(function(data) {
			$("#form_city").html(data);
			$("#form_city").removeAttr("disabled");
		})
	}

	function select_city() {
		set_address("city", $("#form_city option:selected").html());
	}

	function change_district() {
		let val = $("#form_district").val();
		if (val.length > 2)
			set_address("district", val);
	}

	function change_postal_code() {
		let val = $("#form_postal_code").val();
		if (val.length > 4)
			set_address("postal_code", val);
		else
			set_address("postal_code", "");
	}

	function set_address(key, value) {
		full_address[key] = value;
		// let address = full_address.join(" ");
		$("#IframeMap").attr("src", `https://maps.google.com/maps?width=100%&height=600&hl=en&q=${full_address.province} ${full_address.city} ${full_address.district} ${full_address.postal_code}&ie=UTF8&t=&z=11&iwloc=B&output=embed&disableDefaultUI=true`);
	}

	$("#label_nonpkp_business_type, #label_pkp_business_type").click(function() {
		$("#form_container_responsible_person").html(
			`<div class="form-group">
				<input type="text" name="responsible_person_name" class="form-control auth-form-input" placeholder="<?php echo trans("full_name"); ?>" value="<?php echo set_value("full_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
			</div>
			<div class="form-group">
				<input type="text" name="responsible_person_position" class="form-control auth-form-input" placeholder="<?php echo trans("user_position"); ?>" value="<?php echo set_value("full_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
			</div>
			<div class="m-b-30 form-group">
				<label class="control-label font-600">Upload Surat Ijin Usaha Perdagangan (Opsional)</label>
				<input type="file" class="form-control auth-form-input" name="siup_document" id="form_selected_document">
			</div>
			`);
	});
	$("#label_individual_business_type").click(function() {
		$("#form_container_responsible_person").html(
			`<div class="form-group">
				<input type="text" name="nik" class="form-control auth-form-input" placeholder="<?php echo trans("nik"); ?>" value="<?php echo set_value("nik"); ?>" required>
			</div>`
		);
		$("#form_individual_business_type").prop("checked", true);
	});

	// $("#label_nonpkp_business_type").click(function() {
	// 	$("#form_nonpkp_business_type").prop("checked", true);
	// });
	// $("#label_pkp_business_type").click(function() {
	// 	$("#form_pkp_business_type").prop("checked", true);
	// });

	// $("#label_umkm_business_type").click(function() {
	// 	$("#form_umkm_business_type").prop("checked", true);
	// });
	// $("#label_nonumkm_business_type").click(function() {
	// 	$("#form_nonumkm_business_type").prop("checked", true);
	// });

	var myInput = document.getElementById("password");
	var huruf_kecil = document.getElementById("huruf_kecil");
	var huruf_besar = document.getElementById("huruf_besar");
	var angka = document.getElementById("angka");
	var panjang = document.getElementById("panjang");

	// When the user clicks on the password field, show the message box
	myInput.onfocus = function() {
		document.getElementById("pesan").style.display = "block";
	}

	// When the user clicks outside of the password field, hide the message box
	myInput.onblur = function() {
		document.getElementById("pesan").style.display = "none";
	}

	// When the user starts to type something inside the password field
	myInput.onkeyup = function() {
		// Validate lowercase letters
		var lowerCaseLetters = /[a-z]/g;
		if (myInput.value.match(lowerCaseLetters)) {
			huruf_kecil.classList.remove("tidakbenar");
			huruf_kecil.classList.add("benar");
		} else {
			huruf_kecil.classList.remove("benar");
			huruf_kecil.classList.add("tidakbenar");
		}

		// Validate capital letters
		var upperCaseLetters = /[A-Z]/g;
		if (myInput.value.match(upperCaseLetters)) {
			huruf_besar.classList.remove("tidakbenar");
			huruf_besar.classList.add("benar");
		} else {
			huruf_besar.classList.remove("benar");
			huruf_besar.classList.add("tidakbenar");
		}

		// Validate numbers
		var numbers = /[0-9]/g;
		if (myInput.value.match(numbers)) {
			angka.classList.remove("tidakbenar");
			angka.classList.add("benar");
		} else {
			angka.classList.remove("benar");
			angka.classList.add("tidakbenar");
		}

		// Validate length
		if (myInput.value.length >= 8) {
			panjang.classList.remove("tidakbenar");
			panjang.classList.add("benar");
		} else {
			panjang.classList.remove("benar");
			panjang.classList.add("tidakbenar");
		}
	}
</script>
<!-- Wrapper End-->