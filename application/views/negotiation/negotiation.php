<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<!-- BREADCRUMB -->
			<div class="col-12">
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
			<div class="col-sm-12">
				<?php if ($products != null) : ?>
					<div class="shopping-cart">
						<div class="row">
							<div class="col-sm-12 col-lg-8">
								<div class="left">
									<h1 class="cart-section-title"><?= trans("negotiation"); ?> (<?= count($products) ?>)</h1>
									<?php if (!empty($products)) : ?>
										<?php foreach ($products as $product) : ?>
											<div class="item">
												<div class="row">
													<div class="cart-item-image">
														<div class="img-cart-product">
															<a href="<?= generate_product_url($product); ?>">
																<img src="<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?= get_product_image($product->id, 'image_small'); ?>" alt="<?= html_escape($product->product_title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
															</a>
														</div>
													</div>
													<div class="cart-item-details">
														<?php if ($product->product_type == 'digital') : ?>
															<div class="list-item">
																<label class="label-instant-download label-instant-download-sm"><i class="icon-download-solid"></i><?= trans("instant_download"); ?></label>
															</div>
														<?php endif; ?>
														<div class="list-item text-wrap">
															<a href="<?= generate_product_url($product); ?>">
																<?= html_escape($product->title); ?>
															</a>
															<?php if ($product->stock == 0) : ?>
																<div class="lbl-enough-quantity"><?= trans("out_of_stock"); ?></div>
															<?php endif; ?>
														</div>
														<div class="list-item seller">
															<?= trans("by"); ?>&nbsp;<a href="<?= generate_profile_url($product->vendor->slug) ?>"><?= $product->vendor->username ?></a>
														</div>
														<div class="list-item m-t-15">
															<label><?= trans("unit_price"); ?>:</label>
															<strong class="lbl-price">
																<?= $product->unit_price_formatted; ?>
															</strong>
														</div>
														<div class="list-item">
															<label><?= trans("total"); ?>:</label>
															<strong class="lbl-price"><?= $product->total_price_with_ppn ?></strong>
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
													<a href="<?= base_url('negotiation/change_conversation' . "?product_id=" . $product->id) ?>" class="btn btn-sm btn-dark text-white h-100">Pilih produk</a>
													<a class="btn btn-sm btn-success mx-1 text-white h-100" data-toggle="modal" data-target="#modal-negotiation-<?= $product->id ?>">Kirim Penawaran</a>
													<a href="<?= base_url('negotiation/delete_negotiation') ?>"></a>
												</div>
											</div>
									<?php endforeach;
									endif; ?>
								</div>
							</div>
							<div class="col-sm-12 col-lg-4">
								<div class="right">
									<!-- MESSAGE -->
									<div class="col-sm-12 col-message-content">
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
													<div class="message-list-item">
														<div class="message-list-item-row-received">
															<div class="user-avatar">
																<div class="message-user">
																	<img src="<?= $conversation->user_img ?>" alt="<?= $conversation->user_username ?>" class="img-profile">
																</div>
															</div>
															<div class="user-message">
																<div class="message-text">
																	<?= html_escape($message->message); ?>
																</div>
																<span class="time"><?= time_ago($message->created_at); ?></span>
															</div>
														</div>
													</div>
													<div class="message-list-item">
														<div class="message-list-item-row-sent">
															<div class="user-message">
																<div class="message-text">
																	<?= html_escape($message->message); ?>
																</div>
																<span class="time"><?= time_ago($message->created_at); ?></span>
															</div>
															<div class="user-avatar">
																<div class="message-user">
																	<img src="<?= $conversation->user_image ?>" alt="<?= $conversation->user_username ?>" class="img-profile">
																</div>
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											</div>

											<div class="message-reply">
												<!-- form start -->
												<form action="<?= base_url('negotiation') ?>" method="POST">
													<input type="hidden" name="conversation_id" value="<?= $conversation->id; ?>">
													<input type="hidden" name="vendor_id" value="<?= $conversation->vendor_id; ?>">
													<div class="form-group m-b-10">
														<textarea class="form-control form-textarea" name="message" placeholder="<?= trans('write_a_message'); ?>" required></textarea>
													</div>
													<div class="form-group">
														<button type="submit" class="btn btn-md btn-custom float-right"><i class="icon-send"></i> <?= trans("send"); ?></button>
													</div>
												</form>
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
		<?php else : ?>
		<?php endif; ?>
		</div>
	</div>
</div>
</div>
<?php foreach ($products as $product) : ?>
	<div class="modal fade" id="modal-negotiation-<?= $product->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content modal-custom">
				<div class="modal-header">
					<h5 class="modal-title"><?= trans("submit_a_quote"); ?></h5>
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true"><i class="icon-close"></i> </span>
					</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="product_id" class="form-control" value="<?= $product->id ?>">
					<div class="form-group">
						<label class="control-label"><?= trans('price'); ?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-group-text-currency" id="basic-addon1"><?= get_currency($this->payment_settings->default_product_currency); ?></span>
							</div>
							<input type="text" name="nego_price" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" placeholder="<?= $this->input_initial_price; ?>" required>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label"><?= trans('shipping_cost'); ?></label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text input-group-text-currency" id="basic-addon1"><?= get_currency($this->payment_settings->default_product_currency); ?></span>
							</div>
							<input type="text" name="shipping_cost" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" placeholder="<?= $this->input_initial_price; ?>" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?= trans("close"); ?></button>
					<button type="submit" class="btn btn-md btn-custom"><?= trans("submit"); ?></button>
				</div>
				<?= form_close(); ?>
				<!-- form end -->
			</div>
		</div>
	</div>
<?php endforeach ?>
<!-- Wrapper End-->