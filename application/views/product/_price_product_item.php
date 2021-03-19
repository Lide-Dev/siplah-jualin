<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($product->is_free_product == 1): ?>
    <span class="price-free"><?php echo trans("free"); ?></span>
<?php else: ?>
    <?php if ($product->listing_type == 'bidding'): ?>
        <a href="<?php echo generate_product_url($product); ?>" class="a-meta-request-quote"><?php echo trans("request_a_quote") ?></a>
    <?php else:

        if (!empty($product->price)):
            if (!empty($product->discount_rate)): ?>
                <del class="discount-original-price">
                    <?php echo price_formatted($product->price, $product->currency); ?>
                </del>
            <?php endif; ?>
            <span class="price"><?php echo price_formatted(calculate_product_price($product->price, $product->discount_rate), $product->currency); ?></span>
        <?php endif;
    endif;
endif; ?>
