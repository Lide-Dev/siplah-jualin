<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<!-- BREADCRUMB -->
			<div class="col">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?= lang_base_url(); ?>"><?= trans("home"); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
					</ol>
				</nav>
			</div>
			<!-- END OF BREADCRUMB -->
		</div>
		<div class="row">
			<div class="col">
				<div class="shopping-cart">
					<div class="row">
						<div class="col-md-7 col-sm-4">
							<h1 class="cart-section-title"><?= trans("negotiation"); ?> (<?= count($conversations) ?>)</h1>
							<?php if (!empty($conversations)) : ?>
								<?php foreach ($conversations as $conversation) : ?>
									<?php $product = $conversation->product ?>
									<?php $negotiation = $conversation->last_nego ?>
									<div class="item">
										<div class="row">
											<div class="cart-item-image">
												<div class="img-cart-product">
													<a href="<?= generate_product_url($product); ?>">
														<img src="<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?= get_product_image($product->id, 'image_small'); ?>" alt="<?= html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
													</a>
												</div>
											</div>
											<div class="cart-item-details">
												<div class="list-item text-wrap">
													<a href="<?= generate_product_url($product); ?>">
														<?= html_escape($product->title); ?>
													</a>
													<?php if ($product->stock == 0) : ?>
														<div class="lbl-enough-quantity"><?= trans("out_of_stock"); ?></div>
													<?php endif; ?>
												</div>
												<div class="list-item seller">
													<?= trans("by"); ?>&nbsp;<a href="<?= generate_profile_url($conversation->seller->slug) ?>"><?= $conversation->seller->username ?></a>
												</div>
												<div class="list-item m-t-15">
													<label><?= trans("unit_price"); ?>:</label>
													<strong class="lbl-price">
														<?= price_formatted($product->price, $product->currency) ?>
													</strong>
												</div>
												<div class="list-item m-t-15">
													<label><?= trans("last_nego_price"); ?>:</label>
													<strong class="lbl-price">
														<?= price_formatted($negotiation->product_last_price, $product->currency) ?>
													</strong>
												</div>
												<?php if (!empty($product->vat_rate)) : ?>
													<div class="list-item">
														<label><?= trans("vat"); ?>&nbsp;(<?= $product->vat_rate; ?>%):</label>
														<strong class="lbl-price"><?= $product->ppn_formatted ?></strong>
													</div>
												<?php endif; ?>
											</div>
										</div>
										<div class="row my-2 justify-content-end">
											<a href="<?= base_url('negotiation' . "?conversation_id=" . $conversation->id) ?>" class="btn btn-sm btn-dark text-white h-100">Pilih produk</a>
											<a class="btn btn-sm btn-success mx-1 text-white h-100" data-toggle="modal" data-target="#modal-negotiation-<?= $conversation->id ?>">Kirim Penawaran</a>
										</div>
										<?php if (!empty($negotiation)) : ?>
											<div class="row my-2 border p-3">
												<div class="col-md-9 align-self-start">
													<div class="row">
														<label><?= trans("last_nego_price"); ?>:</label>
														<strong class="lbl-price">
															<?= price_formatted($negotiation->product_last_price, $product->currency) ?>
														</strong>
													</div>
													<div class="row">
														<label><?= trans("last_nego_shipping"); ?>:</label>
														<strong class="lbl-price">
															<?= price_formatted($negotiation->shipping_last_price, $product->currency) ?>
														</strong>
													</div>
												</div>
												<?php if ($user_id != $negotiation->negotiator_id) : ?>
													<div class="col-md-3 align-self-end">
														<a href="" class="btn btn-sm btn-danger text-white">Tolak</a>
														<a href="" class="btn btn-sm mx-1 btn-success text-white">Terima</a>
													</div>
												<?php endif ?>
											</div>
										<?php endif ?>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<div class="col-md-5 col-sm-8">
							<!-- MESSAGE -->
							<div class="col">
								<?php if (!empty($conversation)) : ?>
									<div class="row-custom messages-head">
										<div class="sender-head">
											<div class="left">
												<img src="<?= $conversation->seller_img ?>" alt="<?= html_escape($conversation->seller_username); ?>" class="img-profile">
											</div>
											<div class="right">
												<strong class="username"><?= html_escape($conversation->seller_username); ?>
													<?php if ($conversation->is_seller_verified) : ?>
														<i class="icon-verified icon-verified-member">
														</i>
													<?php endif ?>
												</strong>
												<p class="subject m-0"><?= html_escape($conversation->subject); ?></p>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="row-custom messages-content">
									<div id="message-custom-scrollbar" class="messages-list">
										<?php foreach ($messages as $message) : ?>
											<!-- Start Sender -->
											<?php if ($user_id == $message->sender_id) : ?>
												<div class="message-list-item">
													<div class="message-list-item-row-sent">
														<div class="user-message">
															<div class="message-text">
																<?= $message->message ?>
															</div>
															<span class="time"><?= time_ago($message->created_at); ?></span>
														</div>
														<div class="user-avatar">
															<div class="message-user">
																<img src="<?= get_user_avatar_by_id($message->sender_id) ?>" alt="<?= $conversation->user_username ?>" class="img-profile">
															</div>
														</div>
													</div>
												</div>
												<!-- End Sender -->
											<?php else : ?>
												<!-- Start Receiver -->
												<div class="message-list-item">
													<div class="message-list-item-row-received">
														<div class="user-avatar">
															<div class="message-user">
																<img src="<?= get_user_avatar_by_id($message->receiver_id) ?>" alt="<?= $conversation->user_username ?>" class="img-profile">
															</div>
														</div>
														<div class="user-message">
															<div class="message-text">
																<?= $message->message ?>
															</div>
															<span class="time"><?= time_ago($message->created_at); ?></span>
														</div>
													</div>
												</div>
												<!-- End Receiver -->
											<?php endif ?>
										<?php endforeach; ?>
									</div>

									<div class="message-reply">
										<!-- form start -->
										<?= form_open(base_url('negotiation/send_message')) ?>
										<input type="hidden" name="conversation_id" value="<?= $this->input->get('conversation_id') ?>">
										<div class="form-group m-b-10">
											<textarea class="form-control form-textarea" name="message" placeholder="<?= trans('write_a_message'); ?>" required></textarea>
										</div>
										<div class="form-group">
											<button type="submit" class="btn btn-md btn-custom float-right"><i class="icon-send"></i> <?= trans("send"); ?></button>
										</div>
										<?= form_close() ?>
										<!-- form end -->
									</div>
								</div>
							</div>
						</div>
						<!-- END OF MESSAGE -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php foreach ($conversations as $conversation) : ?>
	<?php $product = $conversation->product ?>
	<?= form_open(base_url('negotiation/make_offer')) ?>
	<div class="modal fade" id="modal-negotiation-<?= $conversation->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content modal-custom">
				<div class="modal-header">
					<h5 class="modal-title"><?= trans("submit_a_quote"); ?></h5>
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true"><i class="icon-close"></i> </span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" name="conversation_id" value="<?= $conversation->id ?>">
						<label class="control-label"><?= trans('price'); ?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-group-text-currency" id="basic-addon1"><?= get_currency($this->payment_settings->default_product_currency); ?></span>
							</div>
							<input type="text" name="offer_price" class="form-control form-input price-input validate-price-input" placeholder="<?= $this->input_initial_price; ?>" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label"><?= trans('shipping_cost'); ?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-group-text-currency" id="basic-addon1"><?= get_currency($this->payment_settings->default_product_currency); ?></span>
							</div>
							<input type="text" name="offer_shipping" class="form-control form-input price-input validate-price-input" placeholder="<?= $this->input_initial_price; ?>" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?= trans("close"); ?></button>
					<button type="submit" class="btn btn-md btn-custom"><?= trans("submit"); ?></button>
				</div>
			</div>
		</div>
	</div>
	<?= form_close() ?>
<?php endforeach ?>
<!-- Wrapper End-->