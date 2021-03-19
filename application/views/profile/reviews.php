<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("followers"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="profile-page-top">
                    <!-- load profile details -->
                    <?php $this->load->view("profile/_profile_user_info"); ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <!-- load profile nav -->
                <?php $this->load->view("profile/_profile_tabs"); ?>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="profile-tab-content">

                    <div id="user-review-result" class="user-reviews">
                        <div class="reviews-container">
                            <div class="col-12">
                                <div class="review-total">
                                    <label class="label-review"><?php echo trans("reviews"); ?>&nbsp;(<?php echo $user_rating->count; ?>)</label>
                                    <?php if (!empty($reviews)):
                                        $this->load->view('partials/_review_stars', ['review' => $user_rating->rating]);
                                    endif; ?>
                                </div>
                                <?php if (empty($reviews)): ?>
                                    <p class="no-comments-found"><?php echo trans("no_reviews_found"); ?></p>
                                <?php else: ?>
                                    <ul class="list-unstyled list-reviews">
                                        <?php foreach ($reviews as $review): ?>
                                            <li class="media">
                                                <a href="<?php echo generate_profile_url($review->user_slug); ?>">
                                                    <img src="<?php echo get_user_avatar_by_id($review->user_id); ?>" alt="<?php echo get_shop_name_by_user_id($review->user_id); ?>">
                                                </a>
                                                <div class="media-body">
                                                    <?php $review_product = get_product($review->product_id);
                                                    if (!empty($review_product)):?>
                                                        <div class="row-custom m-b-10">
                                                            <a href="<?php echo generate_product_url_by_slug($review_product->slug); ?>"><strong><?php echo trans("product"); ?>:&nbsp;</strong><?php echo html_escape($review_product->title); ?></a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="row-custom">
                                                        <?php $this->load->view('partials/_review_stars', ['review' => $review->rating]); ?>
                                                    </div>
                                                    <div class="row-custom">
                                                        <a href="<?php echo generate_profile_url($review->user_slug); ?>">
                                                            <h5 class="username"><?php echo get_shop_name_by_user_id($review->user_id); ?></h5>
                                                        </a>
                                                    </div>
                                                    <div class="row-custom">
                                                        <div class="review">
                                                            <?php echo html_escape($review->review); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row-custom">
                                                        <span class="date"><?php echo time_ago($review->created_at); ?></span>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 m-t-15">
                                <div class="float-right">
                                    <?php echo $this->pagination->create_links(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-custom">
                        <!--Include banner-->
                        <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "profile", "class" => "m-t-30"]); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Wrapper End-->

<!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => null]); ?>

