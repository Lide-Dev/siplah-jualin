<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("profile"); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="profile-page-top">
                    <!-- load profile details -->
                    <?php $this->load->view("profile/_profile_user_info"); ?>
                </div>
            </div>
        

        <!-- search -->
        <div class="col-6">
        <div class="top-search-bar">
            <?php echo form_open(generate_url('search'), ['id' => 'form_validate_search', 'class' => 'form_search_main', 'method' => 'get']); ?>
            <?php if ($this->general_settings->multi_vendor_system == 1) : ?>
                <div class="left">
                    <!-- <div class="dropdown search-select">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                            <?php if (isset($search_type)) : ?>
                                <?php echo trans("category"); ?>
                            <?php else : ?>
                                <?php echo trans("product"); ?>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" data-value="product" href="javascript:void(0)"><?php echo trans("category"); ?></a>
                            <a class="dropdown-item" data-value="member" href="javascript:void(0)"><?php echo trans("store"); ?></a>
                        </div>
                    </div> -->
                    <?php if (isset($search_type)) : ?>
                        <input type="hidden" class="search_type_input" name="search_type" value="product">
                    <?php else : ?>
                        <input type="hidden" class="search_type_input" name="search_type" value="product">
                    <?php endif; ?>
                </div>
                <div class="right">
                    <input type="text" name="search" maxlength="300" pattern=".*\S+.*" id="input_search" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search_products"); ?>" required autocomplete="off">
                    <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
                    <div id="response_search_results" class="search-results-ajax"></div>
                </div>
            <?php else : ?>
                <input type="text" name="search" maxlength="300" pattern=".*\S+.*" id="input_search" class="form-control input-search" value="<?php echo (!empty($filter_search)) ? $filter_search : ''; ?>" placeholder="<?php echo trans("search_products"); ?>" required autocomplete="off">
                <button class="btn btn-default btn-search"><i class="icon-search"></i></button>
                <div id="response_search_results" class="search-results-ajax"></div>
            <?php endif; ?>
            <?php echo form_close(); ?>
        </div>
        </div>
        <!-- end of search -->
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-3">
                <!-- load profile nav -->
                <?php $this->load->view("profile/_profile_tabs"); ?>
            </div>

            <div class="col-sm-12 col-md-9">
                <div class="profile-tab-content">
                    <?php if ($this->auth_check && $this->auth_user->id == $user->id) :
                        foreach ($products as $product) :
                            $this->load->view('product/_product_item_profile', ['product' => $product, 'promoted_badge' => true]);
                        endforeach;
                    else : ?>
                        <div class="row row-product-items row-product">
                            <!--print products-->
                            <?php foreach ($products as $product) : ?>
                                <div class="col-6 col-sm-6 col-md-6 col-lg-4 col-product">
                                    <?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => true]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-list-pagination">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
                <!-- <div class="row-custom">
                    Include banner
                    <?php $this->load->view("partials/_ad_spaces", ["ad_space" => "profile", "class" => "m-t-30"]); ?>
                </div> -->
            </div>
        </div>
    </div>
</div>
<!-- Wrapper End-->

<!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => null]); ?>