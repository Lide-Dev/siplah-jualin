<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="auth-container">
			<div class="auth-box">
				<div class="row">
					<div class="col-12">
						<h1 class="title"><?php echo trans("login"); ?></h1>
						<!-- form start -->
                        <form id="form_login" novalidate="novalidate">
                            <div class="social-login-cnt">
                                <?php $this->load->view("partials/_social_login", ["or_text" => trans("login_with_email")]); ?>
                            </div>
                            <!-- include message block -->
                            <div id="result-login" class="font-size-13"></div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control auth-form-input" placeholder="<?php echo trans("email_address"); ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control auth-form-input" placeholder="<?php echo trans("password"); ?>" minlength="4" required>
                            </div>
                            <div class="form-group text-right">
                                <a href="<?php echo generate_url("forgot_password"); ?>" class="link-forgot-password"><?php echo trans("forgot_password"); ?></a>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-md btn-custom btn-block"><?php echo trans("login"); ?></button>
                            </div>

                            <p class="p-social-media m-0 m-t-5"><?php echo trans("dont_have_account"); ?>&nbsp;<a href="<?php echo generate_url("register"); ?>" class="link"><?php echo trans("register"); ?></a></p>
                        </form>
                        <!-- form end -->
						<!-- form end -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->