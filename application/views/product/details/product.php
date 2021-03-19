<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-products">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <?php if (!empty($category)):
                            $breadcrumb = get_parent_categories_array($category->id);
                            if (!empty($breadcrumb)):
                                foreach ($breadcrumb as $item_breadcrumb):
                                    $item_category = get_category_by_id($item_breadcrumb->id);
                                    if (!empty($item_category)):?>
                                        <li class="breadcrumb-item"><a href="<?php echo generate_category_url($item_category); ?>"><?php echo category_name($item_category); ?></a>
                                        </li>
                                    <?php endif;
                                endforeach;
                            endif;
                        endif; ?>
                    </ol>
                </nav>
            </div>

            <div class="col-12">
                <div class="product-details-container <?php echo ((!empty($video) || !empty($audio)) && item_count($product_images) < 2) ? "product-details-container-digital" : ""; ?>">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6">
                            <div id="product_slider_container">
                                <?php $this->load->view("product/details/_preview"); ?>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <div id="response_product_details" class="product-content-details">
                                <?php $this->load->view("product/details/_product_details"); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="product-description">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab_description" data-toggle="tab" href="#tab_description_content" role="tab" aria-controls="tab_description" aria-selected="true"><?php echo trans("description"); ?></a>
                                </li>
                                <?php if (!empty($custom_fields)): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab_additional_information" data-toggle="tab" href="#tab_additional_information_content" role="tab" aria-controls="tab_additional_information" aria-selected="false"><?php echo trans("additional_information"); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($product->product_type != "digital"): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab_shipping" data-toggle="tab" href="#tab_shipping_content" role="tab" aria-controls="tab_shipping" aria-selected="false"><?php echo trans("shipping_location"); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->general_settings->reviews == 1): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab_reviews" data-toggle="tab" href="#tab_reviews_content" role="tab" aria-controls="tab_reviews" aria-selected="false"><?php echo trans("reviews"); ?>&nbsp;(<?php echo $review_count; ?>)</a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->general_settings->product_comments == 1): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab_comments" data-toggle="tab" href="#tab_comments_content" role="tab" aria-controls="tab_comments" aria-selected="false"><?php echo trans("comments"); ?>&nbsp;(<?php echo $comment_count; ?>)</a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->general_settings->facebook_comment_status == 1): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab_facebook_comments" data-toggle="tab" href="#tab_facebook_comments_content" role="tab" aria-controls="facebook_comments" aria-selected="false"><?php echo trans("facebook_comments"); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>


                            <div id="accordion" class="tab-content">
                                <div class="tab-pane fade show active" id="tab_description_content" role="tabpanel">
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="card-link" data-toggle="collapse" href="#collapse_description_content">
                                                <?php echo trans("description"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                            </a>
                                        </div>
                                        <div id="collapse_description_content" class="collapse-description-content collapse show" data-parent="#accordion">
                                            <div class="description">
                                                <?php echo $product->description; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($custom_fields)): ?>
                                    <div class="tab-pane fade" id="tab_additional_information_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link collapsed" data-toggle="collapse" href="#collapse_additional_information_content">
                                                    <?php echo trans("additional_information"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_additional_information_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                <table class="table table-striped table-product-additional-information">
                                                    <tbody>
                                                    <?php foreach ($custom_fields as $custom_field):
                                                        if (!empty($custom_field->field_value) || !empty($custom_field->field_common_ids)):?>
                                                            <tr>
                                                                <td class="td-left"><?php echo html_escape($custom_field->name); ?></td>
                                                                <td class="td-right"><?php echo get_custom_field_value($custom_field); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($product->product_type != "digital"): ?>
                                    <div class="tab-pane fade" id="tab_shipping_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link collapsed" data-toggle="collapse" href="#collapse_shipping_content">
                                                    <?php echo trans("shipping_location"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_shipping_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                <?php if (!empty($product->shipping_cost_type) && $this->form_settings->shipping == 1): ?>
                                                    <table class="table table-product-shipping">
                                                        <tbody>
                                                        <tr>
                                                            <?php $shipping_cost_type = get_shipping_option_by_key($product->shipping_cost_type, $this->selected_lang->id);
                                                            if (!empty($shipping_cost_type)):
                                                                if ($shipping_cost_type->shipping_cost != 1):?>
                                                                    <td class="td-left"><?php echo trans("shipping_cost"); ?></td>
                                                                    <td class="td-right"><span><?php echo html_escape($shipping_cost_type->option_label); ?></span></td>
                                                                <?php else: ?>
                                                                    <td class="td-left"><?php echo trans("shipping_cost"); ?></td>
                                                                    <td class="td-right"><span><?php echo price_formatted($product->shipping_cost, $product->currency); ?></span></td>
                                                                <?php endif;
                                                            endif; ?>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-left"><?php echo trans("shipping_time"); ?></td>
                                                            <td class="td-right"><span><?php echo trans($product->shipping_time); ?></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="td-left"><?php echo trans("location"); ?></td>
                                                            <td class="td-right"><span><?php echo get_location($product); ?></span></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                <?php endif; ?>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <?php if (!empty($product->country_id)): ?>
                                                            <div class="product-location-map">
                                                                <!--load map-->
                                                                <iframe src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo get_location($product); ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true"
                                                                        frameborder="0" scrolling="no" marginheight="0"
                                                                        marginwidth="0"></iframe>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->general_settings->reviews == 1): ?>
                                    <div class="tab-pane fade" id="tab_reviews_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link collapsed" data-toggle="collapse" href="#collapse_reviews_content">
                                                    <?php echo trans("reviews"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_reviews_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                <div id="review-result">
                                                    <?php $this->load->view('product/details/_reviews'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->general_settings->product_comments == 1): ?>
                                    <div class="tab-pane fade" id="tab_comments_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link collapsed" data-toggle="collapse" href="#collapse_comments_content">
                                                    <?php echo trans("comments"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_comments_content" class="collapse-description-content collapse" data-parent="#accordion">
                                                <input type="hidden" value="<?php echo $comment_limit; ?>" id="product_comment_limit">
                                                <div class="comments-container">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <?php $this->load->view('product/details/_comments'); ?>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="col-comments-inner">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="row-custom row-comment-label">
                                                                            <label class="label-comment"><?php echo trans("add_a_comment"); ?></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <form id="form_add_comment">
                                                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                                                            <?php if (!$this->auth_check): ?>
                                                                                <div class="form-row">
                                                                                    <div class="form-group col-md-6">
                                                                                        <input type="text" name="name" id="comment_name" class="form-control form-input" placeholder="<?php echo trans("name"); ?>">
                                                                                    </div>
                                                                                    <div class="form-group col-md-6">
                                                                                        <input type="email" name="email" id="comment_email" class="form-control form-input" placeholder="<?php echo trans("email_address"); ?>">
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <div class="form-group">
                                                                                <textarea name="comment" id="comment_text" class="form-control form-input form-textarea" placeholder="<?php echo trans("comment"); ?>"></textarea>
                                                                            </div>
                                                                            <?php if (!$this->auth_check):
                                                                                generate_recaptcha();
                                                                            endif; ?>
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
                                                                            </div>
                                                                        </form>
                                                                        <div id="message-comment-result" class="message-comment-result"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($this->general_settings->facebook_comment_status == 1): ?>
                                    <div class="tab-pane fade" id="tab_facebook_comments_content" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="card-link collapsed" data-toggle="collapse" href="#collapse_facebook_comments_content">
                                                    <?php echo trans("facebook_comments"); ?><i class="icon-arrow-down"></i><i class="icon-arrow-up"></i>
                                                </a>
                                            </div>
                                            <div id="collapse_facebook_comments_content" class="collapse-description-content collapse" data-parent="#accordion">

                                                <div class="fb-comments" data-href="<?php echo current_url(); ?>" data-width="100%" data-numposts="5" data-colorscheme="light"></div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="row-custom row-bn">
                    <!--Include banner-->
                    <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product", "class" => "m-b-30"]); ?>
                </div>
            </div>
            <?php if (!empty($user_products)): ?>
                <div class="col-12 section section-related-products m-t-30">
                    <h3 class="title"><?php echo trans("more_from"); ?>&nbsp;<a href="<?php echo generate_profile_url($product->user_slug); ?>"><?php echo html_escape($product->user_username); ?></a></h3>
                    <div class="row row-product">
                        <!--print related posts-->
                        <?php $count = 0;
                        foreach ($user_products as $item):
                            if ($count < 4):?>
                                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
                                    <?php $this->load->view('product/_product_item', ['product' => $item]); ?>
                                </div>
                            <?php endif;
                            $count++;
                        endforeach; ?>
                    </div>
                    <?php if (item_count($user_products) > 4): ?>
                        <div class="row-custom text-center">
                            <a href="<?php echo generate_profile_url($product->user_slug); ?>" class="link-see-more"><span><?php echo trans("view_all"); ?>&nbsp;</span><i class="icon-arrow-right"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($related_products)): ?>
                <div class="col-12 section section-related-products">
                    <h3 class="title"><?php echo trans("you_may_also_like"); ?></h3>
                    <div class="row row-product">
                        <!--print related posts-->
                        <?php
                        $count = 0;
                        foreach ($related_products as $item):
                            if ($count < 4):
                                $item = @(object)$item;
                                if (!empty($item)):?>
                                    <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
                                        <?php $this->load->view('product/_product_item', ['product' => $item]); ?>
                                    </div>
                                <?php endif;
                            endif;
                            $count++;
                        endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <div class="row-custom row-bn">
                    <!--Include banner-->
                    <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "product_bottom", "class" => "m-b-30"]); ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Wrapper End-->

<!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => $product->title]); ?>

<?php if ($this->general_settings->facebook_comment_status == 1):
    echo $this->general_settings->facebook_comment; ?>
    <script>
        $(".fb-comments").attr("data-href", window.location.href);
    </script>
<?php endif; ?>

<!-- Plyr JS-->
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.polyfilled.min.js"></script>
<script>
    const player = new Plyr('#player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#player');
    });
    const audio_player = new Plyr('#audio_player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#audio_player');
    });
</script>

<script>
    $(function () {
        $('.product-description iframe').wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
        $('.product-description iframe').addClass('embed-responsive-item');
    });
</script>
