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

						echo form_open_multipart('register-post/supplier', [
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
								<div class="form-group">
									<label class="control-label font-600 col-sm-6 mt-3" for="form_business_type"><?= trans("business_type") ?></label>
									<ul class="nav nav-pills">
										<li class="nav-item col-sm-4">
											<label class="nav-link border border-muted text-center font-weight-bold <?= set_value("business_type") == "individual" ? "active" : "" ?> " for="form_individual_business_type" id="label_individual_business_type" data-toggle="tab" aria-selected="">Individu</label>
											<input type="radio" name="business_type" id="form_individual_business_type" value="individual" style="display:none" <?= set_value("business_type") == "individual" ? "checked" : "" ?>>
										</li>
										<li class="nav-item col-sm-4">
											<label class="nav-link border border-muted text-center font-weight-bold  <?= set_value("business_type") == "non_pkp" ? "active" : "" ?> <?= empty(set_value("business_type")) ? "active" : "" ?>" for="form_nonpkp_business_type" id="label_nonpkp_business_type" data-toggle="tab" aria-selected=""> Non PKP</label>
											<input type="radio" name="business_type" id="form_nonpkp_business_type" value="non_pkp" style="display:none" <?= set_value("business_type") == "non_pkp" ? "checked" : "" ?> <?= empty(set_value("business_type")) ? "checked" : "" ?>>
										</li>
										<li class="nav-item col-sm-4">
											<label class="nav-link border border-muted text-center font-weight-bold <?= set_value("business_type") == "pkp" ? "active" : "" ?>" for="form_pkp_business_type" id="label_pkp_business_type" data-toggle="tab" aria-selected="">PKP</label>
											<input type="radio" name="business_type" id="form_pkp_business_type" value="pkp" style="display:none" <?= set_value("business_type") == "pkp" ? "checked" : "" ?>>
										</li>
									</ul>
									<?php echo form_error('business_type'); ?>
								</div>

								<div class="form-group">
									<label class="control-label font-600 col-sm-6 mt-3" for="form_umkm_type">Tipe Usaha</label>
									<ul class="nav nav-pills">
										<li class="nav-item col-sm-6">
											<label class="nav-link border border-muted text-center font-weight-bold <?= set_value("umkm")  == "umkm" || empty(set_value("umkm")) ? "active" : "" ?>" for="form_umkm_business_type" id="label_umkm_business_type" data-toggle="tab" aria-selected="">UMKM</label>
											<input type="radio" name="umkm" id="form_umkm_business_type" value="umkm" style="display:none" <?= set_value("umkm") == "umkm" ? "checked" : "" ?> <?= empty(set_value("umkm")) ? "checked" : "" ?>>
										</li>
										<li class="nav-item col-sm-6">
											<label class="nav-link border border-muted text-center font-weight-bold <?= set_value("umkm") == "non_umkm" ? "active" : "" ?>" for="form_nonumkm_business_type" <?= set_value("umkm") == "non_umkm" ? "active" : "" ?> id="label_nonumkm_business_type" data-toggle="tab" aria-selected=""> Non UMKM</label>
											<input type="radio" name="umkm" id="form_nonumkm_business_type" value="non_umkm" style="display:none" <?= set_value("umkm") == "non_umkm" ? "checked" : "" ?>>
										</li>
									</ul>
									<?php echo form_error('umkm'); ?>
								</div>

								<div class="form-group">
									<input type="text" name="business_name" class="form-control auth-form-input" placeholder="<?php echo trans("business_name"); ?>" value="<?php echo set_value("business_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
									<?php echo form_error('business_name'); ?>
								</div>
								<div class="form-group">
									<input type="text" name="npwp" class="form-control auth-form-input" placeholder="<?php echo trans("npwp"); ?>" value="<?php echo set_value("npwp"); ?>" required>
									<?php echo form_error('npwp'); ?>
								</div>
								<!-- END OF PROFILE business -->

								<!-- UPLOAD DOC NPWP -->

								<div class="m-b-30 form_group pb-3">
									<label class="control-label font-600"><?php echo trans("upload_npwp"); ?></label>
									<input type="file" class="form-control auth-form-input" name="npwp_document" id="form_npwp_document">
									<?php echo form_error('npwp_document'); ?>
								</div>
							</div>
						</div>
						<!-- END OF UPLOAD DOC NPWP -->
						<!-- NIB -->
						<div class="form-group">
							<input type="text" name="nib" class="form-control auth-form-input" placeholder="<?php echo trans("nib"); ?>" value="<?php echo set_value("nib"); ?>" required>
							<?php echo form_error('nib'); ?>
						</div>

						<div class="m-b-30 form-group">
							<label class="control-label font-600"><?php echo "Unggah Dokumen NIB" ?></label>
							<input type="file" class="form-control auth-form-input" name="nib_document" id="form_selected_document">
							<?php echo form_error('nib_document'); ?>
						</div>

						<!-- END OF NIB -->
						<!-- ADDRESS -->
						<div class="form-group">
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
						<div class="form-group">
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
						<!-- END OF BANK ACCOUNT -->
						<!-- RESPONSIBLE PERSON -->
						<?php if (set_value("business_type") != "individual" || empty(set_value("business_type"))) : ?>
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
								</div>
							</div>
						<?php else : ?>
							<h4 class="title-auth">3. <?php echo trans("responsible_title"); ?></h4>
							<div class="form-group">
								<input type="text" name="nik" class="form-control auth-form-input" placeholder="<?php echo trans("nik"); ?>" value="<?php echo set_value("nik"); ?>" required>
							</div>
						<?php endif; ?>
						<h4 class="title-auth">4. <?php echo trans("create_user"); ?></h4>
						<div class="form-group">
							<input type="email" name="email_address" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo set_value("email_address"); ?>" required>
							<?php echo form_error('email'); ?>
						</div>
						<div class="form-group">
							<input type="number" name="phone_number" class="form-control auth-form-input" placeholder="<?php echo trans("phone_number"); ?>" value="<?php echo set_value("phone_number"); ?>" required>
							<?php echo form_error('phone_number'); ?>
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" required>
							<?php echo form_error('password'); ?>
						</div>
						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control auth-form-input" placeholder="<?php echo trans("password_confirm"); ?>" required>
							<?php echo form_error('confirm_password'); ?>
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

	$("#label_nonpkp_business_type").click(function() {
		$("#form_nonpkp_business_type").prop("checked", true);
	});
	$("#label_pkp_business_type").click(function() {
		$("#form_pkp_business_type").prop("checked", true);
	});

	$("#label_umkm_business_type").click(function() {
		$("#form_umkm_business_type").prop("checked", true);
	});
	$("#label_nonumkm_business_type").click(function() {
		$("#form_nonumkm_business_type").prop("checked", true);
	});
</script>
<!-- Wrapper End-->