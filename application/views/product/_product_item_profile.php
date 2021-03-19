<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-item product-item-horizontal">
	<div class="row row-product">
		<div class="col-12 col-sm-4">
			<div class="item-image">
				<a class="item-wishlist-button item-wishlist-enable" data-product-id="<?php echo $product->id; ?>"></a>
				<a href="<?php echo generate_product_url($product); ?>">
					<div class="img-product-container">
						<img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
					</div>
				</a>
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="row-custom item-details">
				<h3 class="product-title">
					<a href="<?php echo generate_product_url($product); ?>"><?php echo html_escape($product->title); ?></a>
				</h3>
				<?php if ($product->is_promoted && $this->general_settings->promoted_products == 1 && isset($promoted_badge) && $promoted_badge == true): ?>
					<span class="badge badge-dark badge-promoted"><?php echo trans("featured"); ?>&nbsp;&nbsp;&nbsp;(<?php echo date_difference($product->promote_end_date, date('Y-m-d H:i:s')) . " " . trans("days_left"); ?>)</span>
				<?php endif; ?>
				<p class="product-user text-truncate">
					<a href="<?php echo generate_profile_url($product->user_slug); ?>">
						<?php echo get_shop_name_product($product); ?>
					</a>
				</p>
				<!--stars-->
				<?php if ($this->general_settings->reviews == 1) {
					$this->load->view('partials/_review_stars', ['review' => $product->rating]);
				} ?>
				<div class="item-meta">
					<div class="price-product-item-horizontal">
						<?php $this->load->view('product/_price_product_item', ['product' => $product]); ?>
					</div>
                    <span class="item-wishlist"><i class="icon-heart-o"></i>&nbsp;<?php echo $product->wishlist_count; ?></span>
                    <span class="item-wishlist"><i class="icon-eye"></i>&nbsp;<?php echo $product->hit; ?></span>
				</div>
			</div>
			<div class="row-custom m-t-10">
				<?php if (!$product->is_promoted && $this->general_settings->promoted_products == 1): ?>
					<a href="<?php echo generate_url("promote_product", "pricing") . "/" . $product->id; ?>?type=exist" class="btn btn-sm btn-outline-gray btn-profile-option"><i class="icon-plus"></i>&nbsp;<?php echo trans("promote"); ?></a>
				<?php endif; ?>
				<a href="<?php echo generate_url("sell_now", "edit_product") . "/" . $product->id; ?>" class="btn btn-sm btn-outline-gray btn-profile-option"><i class="icon-edit"></i>&nbsp;<?php echo trans("edit"); ?></a>
				<a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="delete_product(<?php echo $product->id; ?>,'<?php echo trans("confirm_product"); ?>');"><i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
			</div>
		</div>
	</div>
</div>
