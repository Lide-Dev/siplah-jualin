<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-item product-item-horizontal">
	<div class="row">
		<div class="col-12 col-sm-4">
			<div class="item-image">
				<a class="item-wishlist-button item-wishlist-enable" data-product-id="<?php echo $product->id; ?>"></a>
				<div class="img-product-container">
					<img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
				</div>
			</div>
		</div>
		<div class="col-12 col-sm-8">
			<div class="row-custom item-details">
				<h3 class="product-title">
					<?php echo html_escape($product->title); ?>
				</h3>
				<p class="product-user text-truncate">
					<a href="<?php echo generate_profile_url($product->user_slug); ?>">
						<?php echo get_shop_name_product($product); ?>
					</a>
				</p>
			</div>
			<div class="row-custom m-t-10">
				<a href="<?php echo generate_url("sell_now") . "/" . $product->id; ?>" class="btn btn-sm btn-outline-gray btn-profile-option m-r-2"><i class="icon-edit"></i>&nbsp;<?php echo trans("edit"); ?></a>
				<a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="delete_draft(<?php echo $product->id; ?>,'<?php echo trans("confirm_product"); ?>');"><i class="icon-times"></i>&nbsp;<?php echo trans("delete"); ?></a>
			</div>
		</div>
	</div>
</div>
