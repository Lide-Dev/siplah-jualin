<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="product-item">
    <div class="row-custom<?php echo (!empty($product->image_second)) ? ' product-multiple-image' : ''; ?>">
        <a class="item-wishlist-button item-wishlist-enable <?php echo (is_product_in_wishlist($product) == 1) ? 'item-wishlist' : ''; ?>" data-product-id="<?php echo $product->id; ?>"></a>
        <div class="img-product-container">
            <a href="<?php echo generate_product_url($product); ?>">
                <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_product_item_image($product); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product">
                <?php if (!empty($product->image_second)) : ?>
                    <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_product_item_image($product, true); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product img-second">
                <?php endif; ?>
            </a>

            <!-- LABEL PRODUCT-->
            <div class="wp-block-info-over left">
                <h2>
                    <?php if ($product->is_homemade == 1) : ?>
                        <span class="pull-left">
                            <a style="background-color: #3236ff;" href="#">Produk Dalam Negeri</a>
                        </span>
                    <?php else : ?>
                        <span class="pull-left" >
                            <a style="background-color: #008080;" href="#">Produk Luar Negeri</a>
                        </span>
                    <?php endif; ?>
                    <?php if ($product->is_umkm_product == 1) : ?>
                        <span class="pull-left" >
                            <a style="background-color: #ff7032;"  href="#">Produk UMKM</a>
                        </span>
                    <?php endif; ?>
                    <?php if ($product->is_kemendikbud_product == 1) : ?>
                        <span class="pull-left">
                            <a style="background-color: #8436ff;" href="#">Produk Kemendikbud</a>
                        </span>
                    <?php endif; ?>
                </h2>
            </div>
            <!-- END OF LABEL PRODUCT -->

            <?php if (is_multi_vendor_active() && ($this->auth_user->role == "member" || !$this->auth_check)) : ?>
                <div class="product-item-options">
                    <a href="javascript:void(0)" class="item-option btn-add-remove-wishlist" data-toggle="tooltip" data-placement="left" data-product-id="<?php echo $product->id; ?>" data-reload="0" title="<?php echo trans("wishlist"); ?>">
                        <?php if (is_product_in_wishlist($product) == 1) : ?>
                            <i class="icon-heart"></i>
                        <?php else : ?>
                            <i class="icon-heart-o"></i>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php if ($product->is_promoted && $this->general_settings->promoted_products == 1 && isset($promoted_badge) && $promoted_badge == true) : ?>
            <span class="badge badge-dark badge-promoted"><?php echo trans("featured"); ?></span>
        <?php endif; ?>
    </div>
    <div class="row-custom item-details">
        <h3 class="product-title">
            <a href="<?php echo generate_product_url($product); ?>"><?php echo html_escape($product->title); ?></a>
        </h3>
        <p class="product-user text-truncate">
            <a href="<?php echo generate_profile_url($product->user_slug); ?>">
                <?php echo get_shop_name_product($product); ?>
            </a>
        </p>

        <?php if (is_multi_vendor_active() && ($this->auth_user->role == "member" || !$this->auth_check)) : ?>
            <div class="product-item-rating">
                <?php if ($this->general_settings->reviews == 1) {
                    $this->load->view('partials/_review_stars', ['review' => $product->rating]);
                } ?>
                <span class="item-wishlist"><i class="icon-heart-o"></i><?php echo $product->wishlist_count; ?></span>
            </div>
        <?php endif; ?>

        <div class="item-meta">
            <?php $this->load->view('product/_price_product_item', ['product' => $product]); ?>
        </div>
    </div>
</div>