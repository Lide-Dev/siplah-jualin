<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $check_option = true; ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="shopping-cart shopping-cart-shipping">
					<div class="row">
						<div class="col-sm-12 col-lg-7">
							<div class="left">
								<h1 class="cart-section-title"><?php echo trans("checkout"); ?></h1>
								<?php if (!$this->auth_check): ?>
									<div class="row m-b-15">
										<div class="col-12 col-md-6">
											<p><?php echo trans("checking_out_as_guest"); ?></p>
										</div>
										<div class="col-12 col-md-6">
											<p class="text-right"><?php echo trans("have_account"); ?>&nbsp;<a href="javascript:void(0)" class="link-underlined" data-toggle="modal" data-target="#loginModal"><?php echo trans("login"); ?></a></p>
										</div>
									</div>
								<?php endif; ?>

								<?php if (!empty($cart_has_physical_product) && $this->form_settings->shipping == 1 && $mds_payment_type != 'promote'): ?>
									<div class="tab-checkout tab-checkout-closed">
										<a href="<?php echo generate_url("cart","shipping"); ?>"><h2 class="title">1.&nbsp;&nbsp;<?php echo trans("shipping_information"); ?></h2></a>
										<a href="<?php echo generate_url("cart","shipping"); ?>" class="link-underlined edit-link"><?php echo trans("edit"); ?></a>
									</div>
								<?php endif; ?>

								<div class="tab-checkout tab-checkout-open">
									<h2 class="title">
										<?php if (!empty($cart_has_physical_product) && $this->form_settings->shipping == 1 && $mds_payment_type != 'promote') {
											echo '2.';
										} else {
											echo '1.';
										} ?>
										&nbsp;<?php echo trans("payment_method"); ?></h2>
									<?php echo form_open('payment-method-post', ['id' => 'form_validate', 'class' => 'validate_terms']); ?>
									<input type="hidden" name="mds_payment_type" value="<?php echo $mds_payment_type ?>">
									<div class="row">
										<div class="col-12">
											<div class="form-group">
												<ul class="payment-options-list">
													<?php if ($this->payment_settings->paypal_enabled): ?>
														<li>
															<div class="option-payment">
																<div class="list-left">
																	<div class="custom-control custom-radio">
																		<input type="radio" class="custom-control-input" id="option_paypal" name="payment_option" value="paypal" checked required>
																		<label class="custom-control-label label-payment-option" for="option_paypal"><?php echo trans("paypal"); ?></label>
																	</div>
																</div>
																<div class="list-right">
																	<label for="option_paypal">
																		<img src="<?php echo base_url(); ?>assets/img/payment/gopay.png">
																		<img src="<?php echo base_url(); ?>assets/img/payment/bank.png">
																		<img src="<?php echo base_url(); ?>assets/img/payment/olshope.png">
																		<img src="<?php echo base_url(); ?>assets/img/payment/midtrans.png">
																		<img src="<?php echo base_url(); ?>assets/img/payment/paypal.png">
																	</label>
																</div>
															</div>
														</li>
														<?php $check_option = false;
													endif; ?>	
													<?php if ($this->payment_settings->bank_transfer_enabled): ?>
														<li>
															<div class="option-payment">
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="option_bank" name="payment_option" value="bank_transfer" required <?php echo ($check_option == true) ? 'checked' : ''; ?>>
																	<label class="custom-control-label label-payment-option" for="option_bank"><?php echo trans("bank_transfer"); ?><br><small><?php echo trans("bank_transfer_exp"); ?></small></label>
																</div>
															</div>
														</li>
														<?php $check_option = false;
													endif; ?>
													<?php if ($this->payment_settings->midtrans_enabled): ?>
														<li>
															<div class="option-payment">
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="option_midtrans" name="payment_option" value="midtrans" required <?php echo ($check_option == true) ? 'checked' : ''; ?>>
																	<label class="custom-control-label label-payment-option" for="option_midtrans">Midtrans</label>
																</div>
															</div>
														</li>
														<?php $check_option = false;
													endif; ?>
													<?php if ($this->auth_check == 1 && $this->payment_settings->cash_on_delivery_enabled && empty($cart_has_digital_product) && $mds_payment_type != 'promote'): ?>
														<li>
															<div class="option-payment">
																<div class="custom-control custom-radio">
																	<input type="radio" class="custom-control-input" id="option_cash_on_delivery" name="payment_option" value="cash_on_delivery" required <?php echo ($check_option == true) ? 'checked' : ''; ?>>
																	<label class="custom-control-label label-payment-option" for="option_cash_on_delivery"><?php echo trans("cash_on_delivery"); ?><br><small><?php echo trans("cash_on_delivery_exp"); ?></small></label>
																</div>
															</div>
														</li>
													<?php endif; ?>
												</ul>
											</div>
											<div class="form-group">
												<div class="custom-control custom-checkbox custom-control-validate-input">
													<input type="checkbox" class="custom-control-input" name="terms" id="checkbox_terms" required>
													<?php $page_terms_condition = get_page_by_default_name("terms_conditions", $this->selected_lang->id); ?>
													<label for="checkbox_terms" class="custom-control-label"><?php echo trans("terms_conditions_exp"); ?>&nbsp;<a href="<?php echo lang_base_url() . $page_terms_condition->slug; ?>" class="link-terms" target="_blank"><strong><?php echo html_escape($page_terms_condition->title); ?></strong></a></label>
												</div>
											</div>

											<div class="form-group m-t-15">
												<?php if ($mds_payment_type != 'promote'): ?>
													<a href="<?php echo generate_url("cart"); ?>" class="link-underlined link-return-cart"><&nbsp;<?php echo trans("return_to_cart"); ?></a>
												<?php endif; ?>
												<button type="submit" name="submit" value="update" class="btn btn-lg btn-custom btn-continue-payment float-right"><?php echo trans("continue_to_payment") ?></button>
											</div>
										</div>
									</div>
									<?php echo form_close(); ?>
								</div>

								<div class="tab-checkout tab-checkout-closed-bordered">
									<h2 class="title">
										<?php if (!empty($cart_has_physical_product) && $this->form_settings->shipping == 1 && $mds_payment_type != 'promote') {
											echo '3.';
										} else {
											echo '2.';
										} ?>
										&nbsp;<?php echo trans("payment"); ?>
									</h2>
								</div>
							</div>
						</div>

						<?php if ($mds_payment_type == 'promote') {
							$this->load->view("cart/_order_summary_promote");
						} else {
							$this->load->view("cart/_order_summary");
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->
