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
		</div>
		<!-- END OF BREADCRUMB -->
		<div class="row">
			<div class="col-sm-12">
				<?php if ($products != null) : ?>
					<div class="shopping-cart">
						<div class="row">
							<div class="col-sm-12 col-lg-8">
								<div class="left">
									<h1 class="cart-section-title"><?= trans("negotiation"); ?> (<?= count($products) ?>)</h1>
									<?php if (!empty($products)) :
										foreach ($products as $product) : ?>
											<div class="item">
												<div class="cart-item-image col-2">
													<div class="img-cart-product">
														<a href="<?= generate_product_url($product); ?>">
															<img src="<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?= get_product_image($product->id, 'image_small'); ?>" alt="<?= html_escape($product->product_title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?= base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
														</a>
													</div>
												</div>
												<div class="cart-item-details col-9">
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
													<!-- <?php if ($product->product_type != 'digital' && $this->form_settings->shipping == 1) : ?>
														<div class="list-item">
															<label><?= trans("shipping"); ?>:</label>
															<strong><?= price_formatted($product->shipping_cost, $product->currency); ?></strong>
														</div>
													<?php endif; ?> -->
												</div>
												<div class="change-conversation col-1">
													<a href="<?= base_url('negotiation/change_conversation' . "?product_id=" . $product->id) ?>" class="btn btn-dark text-white">Pilih produk</a>
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
														<img src="<?= $conversation->vendor_image ?>" alt="<?= html_escape($conversation->vendor_username); ?>" class="img-profile">
													</div>
													<div class="right">
														<strong class="username"><?= html_escape($conversation->vendor_username); ?>
															<?php if ($conversation->is_vendor_verified) : ?>
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
																	<img src="<?= $conversation->user_image ?>" alt="<?= $conversation->user_username ?>" class="img-profile">
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
<!-- Wrapper End-->