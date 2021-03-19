<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if ($product->listing_type != 'bidding'): ?>
    <?php if ($product->is_free_product == 1): ?>
        <strong class="lbl-free"><?php echo trans("free"); ?></strong>
    <?php else:
        if (!empty($price)):
            if (!empty($discount_rate)): ?>
                <strong class="lbl-price">
                    <b class="discount-original-price">
                        <?php echo price_formatted($price, $product->currency); ?>
                        <span class="price-line"></span>
                    </b>
                    <?php echo price_formatted(calculate_product_price($price, $discount_rate), $product->currency); ?>
                </strong>
                <div class="discount-rate">
                    <?php echo discount_rate_format($discount_rate); ?>
                </div>
            <?php else: ?>
                <strong class="lbl-price">
                    <?php echo price_formatted($price, $product->currency); ?>
                </strong>
            <?php endif;
        endif;
    endif;
endif; ?>