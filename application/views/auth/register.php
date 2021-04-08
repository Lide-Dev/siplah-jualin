<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="auth-container">
			<div class="auth-box">
				<div class="row">
					<div class="col-12">
						<h1 class="title"><?php echo trans("seller_registrasion"); ?></h1>
						<!-- form start -->
						<?php
						if ($recaptcha_status) {
							echo form_open('register-post', [
								'id' => 'form_validate', 'class' => 'validate_terms',
								'onsubmit' => "var serializedData = $(this).serializeArray();var recaptcha = ''; $.each(serializedData, function (i, field) { if (field.name == 'g-recaptcha-response') {recaptcha = field.value;}});if (recaptcha.length < 5) { $('.g-recaptcha>div').addClass('is-invalid');return false;} else { $('.g-recaptcha>div').removeClass('is-invalid');}"
							]);
						} else {
							echo form_open('register-post', ['id' => 'form_validate', 'class' => 'validate_terms']);
						}
						?>
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
						<h3 class="title-auth">1. <?php echo trans("profile_bussines"); ?></h3>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label for="form_nosurat">Jenis Usaha</label>
								<ul class="nav nav-pills">
										<li class="nav-item col-md-6">
											<label class="nav-link border border-dark text-center font-weight-bold" for="form_tipesurat1" id="label_tipesurat1" data-toggle="tab" aria-selected=""> Badan Usaha</label>
											<input type="radio" name="tipesurat" id="form_tipesurat1" value="suratmasuk" style="display:none" checked>
										</li>
										<li class="nav-item col-md-6">
											<label class="nav-link border border-dark text-center font-weight-bold" for="form_tipesurat2" id="label_tipesurat2" data-toggle="tab" aria-selected=""> Individu / Perorangan</label>
											<input type="radio" name="tipesurat" id="form_tipesurat2" value="suratkeluar" style="display:none">
										</li>
								</ul>
							</div>
						</div>
						<div class="form-group">
							<input type="text" name="bussines_name" class="form-control auth-form-input" placeholder="<?php echo trans("bussines_name"); ?>" value="<?php echo old("bussines_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="npwp" class="form-control auth-form-input" placeholder="<?php echo trans("npwp"); ?>" value="<?php echo old("npwp"); ?>" required>
						</div>
						<div class="row">
							<div class="col-12 m-b-30">
								<label class="control-label font-600"><?php echo trans("document_upload_title"); ?></label>
								<?php $this->load->view("auth/_document_upload"); ?>
							</div>
						</div>
						<div class="form-group">
							<input type="text" name="complete_address" class="form-control auth-form-input" placeholder="<?php echo trans("complete_address"); ?>" value="<?php echo old("complete_address"); ?>" required>
						</div>
						<div class="form-group">
							<input type="text" name="province" class="form-control auth-form-input" placeholder="<?php echo trans("province"); ?>" value="<?php echo old("province"); ?>" required>
						</div>
						<div class="form-group">
							<input type="text" name="districs" class="form-control auth-form-input" placeholder="<?php echo trans("districs"); ?>" value="<?php echo old("districs"); ?>" required>
						</div>
						<div class="form-group">
							<input type="text" name="sub_distric" class="form-control auth-form-input" placeholder="<?php echo trans("sub_distric"); ?>" value="<?php echo old("sub_distric"); ?>" required>
						</div>
						<div class="form-group">
							<input type="text" name="village" class="form-control auth-form-input" placeholder="<?php echo trans("village"); ?>" value="<?php echo old("village"); ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="postal_code" class="form-control auth-form-input" placeholder="<?php echo trans("postal_code"); ?>" value="<?php echo old("postal_code"); ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="nib" class="form-control auth-form-input" placeholder="<?php echo trans("nib"); ?>" value="<?php echo old("nib"); ?>" required>
						</div>
						<div class="row">
							<div class="col-12 m-b-30">
								<label class="control-label font-600"><?php echo trans("document_upload_title"); ?></label>
								<?php $this->load->view("auth/_document_upload"); ?>
							</div>
						</div>
						<h3 class="title-auth">2. <?php echo trans("bank_title"); ?></h3>
						<div class="form-group">
							<input type="text" name="bank_name" class="form-control auth-form-input" placeholder="<?php echo trans("bank_name"); ?>" value="<?php echo old("bank_name"); ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="account_number" class="form-control auth-form-input" placeholder="<?php echo trans("account_number"); ?>" value="<?php echo old("account_number"); ?>" required>
						</div>
						<div class="form-group">
							<input type="text" name="bank_account_holder" class="form-control auth-form-input" placeholder="<?php echo trans("bank_account_holder"); ?>" value="<?php echo old("bank_account_holder"); ?>" required>
						</div>
						<h3 class="title-auth">3. <?php echo trans("responsible_title"); ?></h3>
						<div class="form-group">
							<input type="text" name="username" class="form-control auth-form-input" placeholder="<?php echo trans("full_name"); ?>" value="<?php echo old("full_name"); ?>" maxlength="<?php echo $this->username_maxlength; ?>" required>
						</div>
						<div class="form-group">
							<input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" value="<?php echo old("email"); ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="phone_number" class="form-control auth-form-input" placeholder="<?php echo trans("phone_number"); ?>" value="<?php echo old("phone_number"); ?>" required>
						</div>
						<div class="form-group">
							<input type="number" name="nik" class="form-control auth-form-input" placeholder="<?php echo trans("nik"); ?>" value="<?php echo old("nik"); ?>" required>
						</div>
						<h3 class="title-auth">4. <?php echo trans("password_title"); ?></h3>
						<div class="form-group">
							<input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" value="<?php echo old("password"); ?>" required>
						</div>
						<div class="form-group">
							<input type="password" name="confirm_password" class="form-control auth-form-input" placeholder="<?php echo trans("password_confirm"); ?>" required>
						</div>
						<div class="form-group m-t-30 m-b-20">
							<div class="custom-control custom-checkbox custom-control-validate-input">
								<input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
								<?php $page_terms_condition = get_page_by_default_name("terms_conditions", $this->selected_lang->id); ?>
								<label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url() . $page_terms_condition->slug; ?>" class="link-terms" target="_blank"><strong><?php echo html_escape($page_terms_condition->title); ?></strong></a></label>
							</div>
						</div>
						<?php if ($recaptcha_status) : ?>
							<div class="recaptcha-cnt">
								<?php generate_recaptcha(); ?>
							</div>
						<?php endif; ?>
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
<!-- Wrapper End-->