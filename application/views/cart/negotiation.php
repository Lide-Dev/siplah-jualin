<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <!-- BREADCRUMB -->
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- END OF BREADCRUMB -->
        <div class="row">
            <div class="col-sm-12">
                <?php if ($cart_items != null) : ?>
                    <div class="shopping-cart">
                        <div class="row">
                            <div class="col-sm-12 col-lg-8">
                                <div class="left">
                                    <h1 class="cart-section-title"><?php echo trans("negotiation"); ?> (<?php echo get_cart_product_count(); ?>)</h1>
                                    <?php if (!empty($cart_items)) :
                                        foreach ($cart_items as $cart_item) :
                                            $product = get_available_product($cart_item->product_id);
                                            if (!empty($product)) : ?>
                                                <div class="item">
                                                    <div class="cart-item-image">
                                                        <div class="img-cart-product">
                                                            <a href="<?php echo generate_product_url($product); ?>">
                                                                <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_product_image($cart_item->product_id, 'image_small'); ?>" alt="<?php echo html_escape($cart_item->product_title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>'">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="cart-item-details">
                                                        <?php if ($product->product_type == 'digital') : ?>
                                                            <div class="list-item">
                                                                <label class="label-instant-download label-instant-download-sm"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="list-item">
                                                            <a href="<?php echo generate_product_url($product); ?>">
                                                                <?php echo html_escape($cart_item->product_title); ?>
                                                            </a>
                                                            <?php if (empty($cart_item->is_stock_available)) : ?>
                                                                <div class="lbl-enough-quantity"><?php echo trans("out_of_stock"); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="list-item seller">
                                                            <?php echo trans("by"); ?>&nbsp;<a href="<?php echo generate_profile_url($product->user_slug); ?>"><?php echo get_shop_name_product($product); ?></a>
                                                        </div>
                                                        <div class="list-item m-t-15">
                                                            <label><?php echo trans("unit_price"); ?>:</label>
                                                            <strong class="lbl-price">
                                                                <?php echo price_formatted($cart_item->unit_price, $cart_item->currency);
                                                                if (!empty($cart_item->discount_rate)) : ?>
                                                                    <span class="discount-rate-cart">
                                                                        (<?php echo discount_rate_format($cart_item->discount_rate); ?>)
                                                                    </span>
                                                                <?php endif; ?>
                                                            </strong>
                                                        </div>
                                                        <div class="list-item">
                                                            <label><?php echo trans("total"); ?>:</label>
                                                            <strong class="lbl-price"><?php echo price_formatted($cart_item->total_price, $cart_item->currency); ?></strong>
                                                        </div>
                                                        <!-- LABEL PPN -->
                                                        <div class="list-item">
                                                            <label><?php echo trans("ppn"); ?>:</label>
                                                            <strong class="lbl-price"><?php echo price_formatted($cart_item->total_price, $cart_item->currency); ?></strong>
                                                        </div>
                                                        <!-- END OF LABEL PPN -->
                                                        <?php if (!empty($product->vat_rate)) : ?>
                                                            <div class="list-item">
                                                                <label><?php echo trans("vat"); ?>&nbsp;(<?php echo $product->vat_rate; ?>%):</label>
                                                                <strong class="lbl-price"><?php echo price_formatted($cart_item->product_vat, $cart_item->currency); ?></strong>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($product->product_type != 'digital' && $this->form_settings->shipping == 1) : ?>
                                                            <div class="list-item">
                                                                <label><?php echo trans("shipping"); ?>:</label>
                                                                <strong><?php echo price_formatted($cart_item->shipping_cost, $cart_item->currency); ?></strong>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                    <?php endif;
                                        endforeach;
                                    endif; ?>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-4">
                                <div class="right">
                                    <!-- MESSAGE -->
                                            <div class="col-sm-12 col-message-content">
                                                <?php
                                                $profile_id = $conversation->sender_id;
                                                if ($this->auth_user->id == $conversation->sender_id) {
                                                    $profile_id = $conversation->receiver_id;
                                                }

                                                $profile = get_user($profile_id);
                                                if (!empty($profile)) : ?>
                                                    <div class="row-custom messages-head">
                                                        <div class="sender-head">
                                                            <div class="left">
                                                                <img src="<?php echo get_user_avatar($profile); ?>" alt="<?php echo html_escape($profile->username); ?>" class="img-profile">
                                                            </div>
                                                            <div class="right">
                                                                <strong class="username"><?php echo html_escape($profile->username); ?></strong>
                                                                <p class="p-last-seen">
                                                                    <span class="last-seen <?php echo (is_user_online($profile->last_seen)) ? 'last-seen-online' : ''; ?>"> <i class="icon-circle"></i> <?php echo trans("last_seen"); ?>&nbsp;<?php echo time_ago($profile->last_seen); ?></span>
                                                                </p>
                                                                <p class="subject m-0"><?php echo html_escape($conversation->subject); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="row-custom messages-content">
                                                    <div id="message-custom-scrollbar" class="messages-list">
                                                        <?php foreach ($messages as $item) :
                                                            if ($item->deleted_user_id != $this->auth_user->id) : ?>
                                                                <?php if ($this->auth_user->id == $item->receiver_id) : ?>
                                                                    <div class="message-list-item">
                                                                        <div class="message-list-item-row-received">
                                                                            <div class="user-avatar">
                                                                                <div class="message-user">
                                                                                    <img src="<?php echo get_user_avatar_by_id($item->sender_id); ?>" alt="" class="img-profile">
                                                                                </div>
                                                                            </div>
                                                                            <div class="user-message">
                                                                                <div class="message-text">
                                                                                    <?php echo html_escape($item->message); ?>
                                                                                </div>
                                                                                <span class="time"><?php echo time_ago($item->created_at); ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php else : ?>
                                                                    <div class="message-list-item">
                                                                        <div class="message-list-item-row-sent">
                                                                            <div class="user-message">
                                                                                <div class="message-text">
                                                                                    <?php echo html_escape($item->message); ?>
                                                                                </div>
                                                                                <span class="time"><?php echo time_ago($item->created_at); ?></span>
                                                                            </div>
                                                                            <div class="user-avatar">
                                                                                <div class="message-user">
                                                                                    <img src="<?php echo get_user_avatar_by_id($item->sender_id); ?>" alt="" class="img-profile">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <div class="message-reply">
                                                        <!-- form start -->
                                                        <?php echo form_open('send-message-post', ['id' => 'form_validate']); ?>
                                                        <input type="hidden" name="conversation_id" value="<?php echo $conversation->id; ?>">
                                                        <?php if ($this->auth_user->id == $conversation->sender_id) : ?>
                                                            <input type="hidden" name="receiver_id" value="<?php echo $conversation->receiver_id; ?>">
                                                        <?php else : ?>
                                                            <input type="hidden" name="receiver_id" value="<?php echo $conversation->sender_id; ?>">
                                                        <?php endif; ?>
                                                        <div class="form-group m-b-10">
                                                            <textarea class="form-control form-textarea" name="message" placeholder="<?php echo trans('write_a_message'); ?>" required></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-md btn-custom float-right"><i class="icon-send"></i> <?php echo trans("send"); ?></button>
                                                        </div>
                                                        <?php echo form_close(); ?>
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