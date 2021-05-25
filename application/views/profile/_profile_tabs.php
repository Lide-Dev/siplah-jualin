<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--profile page tabs-->
<div class="profile-tabs">
    <ul class="nav">
        <?php if (is_multi_vendor_active()): ?>
            <?php if ($user->role == 'admin' || $user->role == 'vendor'): ?>
                <li class="nav-item <?php echo ($active_tab == 'products') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo generate_profile_url($user->slug); ?>">
                        <span><?php echo trans("products"); ?></span>
                        <span class="count">(<?php echo get_user_products_count($user->id); ?>)</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
                <li class="nav-item <?php echo ($active_tab == 'pending_products') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo generate_url("pending_products"); ?>">
                        <span><?php echo trans("pending_products"); ?></span>
                        <span class="count">(<?php echo get_user_pending_products_count($user->id); ?>)</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
                <li class="nav-item <?php echo ($active_tab == 'hidden_products') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo generate_url("hidden_products"); ?>">
                        <span><?php echo trans("hidden_products"); ?></span>
                        <span class="count">(<?php echo get_user_hidden_products_count($user->id); ?>)</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
                <li class="nav-item <?php echo ($active_tab == 'drafts') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo generate_url("drafts"); ?>">
                        <span><?php echo trans("drafts"); ?></span>
                        <span class="count">(<?php echo get_user_drafts_count($user->id); ?>)</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <li class="nav-item <?php echo ($active_tab == 'wishlist') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("wishlist") . "/" . $user->slug; ?>">
                <span><?php echo trans("wishlist"); ?></span>
                <span class="count">(<?php echo get_user_wishlist_products_count($user->id); ?>)</span>
            </a>
        </li>
        <?php if (is_multi_vendor_active()): ?>
            <?php if ($this->auth_check && $this->auth_user->id == $user->id && is_sale_active() && $this->general_settings->digital_products_system == 1): ?>
                <li class="nav-item <?php echo ($active_tab == 'downloads') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo generate_url("downloads"); ?>">
                        <span><?php echo trans("downloads"); ?></span>
                        <span class="count">(<?php echo get_user_downloads_count($user->id); ?>)</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
        <li class="nav-item <?php echo ($active_tab == 'followers') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("followers") . "/" . $user->slug; ?>">
                <span><?php echo trans("followers"); ?></span>
                <span class="count">(<?php echo get_followers_count($user->id); ?>)</span>
            </a>
        </li>
        <li class="nav-item <?php echo ($active_tab == 'following') ? 'active' : ''; ?>">
            <a class="nav-link" href="<?php echo generate_url("following") . "/" . $user->slug; ?>">
                <span><?php echo trans("following"); ?></span>
                <span class="count">(<?php echo get_following_users_count($user->id); ?>)</span>
            </a>
        </li>
        <?php if (($this->general_settings->reviews == 1) && ($user->role == 'admin' || $user->role == 'vendor') && is_multi_vendor_active()): ?>
            <li class="nav-item <?php echo ($active_tab == 'reviews') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?php echo generate_url("reviews") . "/" . $user->slug; ?>">
                    <span><?php echo trans("reviews"); ?></span>
                    <span class="count">(<?php echo $user_rating->count; ?>)</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>


<!-- Search -->
<!--product filters-->
<div class="">
    <div id="collapseFilters" class="product-filters">
        <?php if (!empty($category)):
            if (!empty($parent_category)):?>
                <h4 class="title-all-categories">
                    <a href="<?php echo generate_category_url($parent_category) . $query_string; ?>"><i class="icon-angle-left"></i><?php echo trans("categories_all"); ?></a>
                </h4>
            <?php else: ?>
                <h4 class="title-all-categories">
                    <a href="<?php echo generate_url("products") . $query_string; ?>"><i class="icon-angle-left"></i><?php echo trans("categories_all"); ?></a>
                </h4>
            <?php endif; ?>
        <?php else: ?>
            <h4 class="title-all-categories">
                <a><?php echo trans("categories"); ?></a>
            </h4>
        <?php endif; ?>
        <?php if (!empty($categories)): ?>
            <div class="filter-item">
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php foreach ($categories as $item): ?>
                            <li>
                                <div class="left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" id="cat_<?php echo $item->id; ?>" value="<?php echo $item->id; ?>" <?php echo (!empty($category) && $category->id == $item->id) ? 'checked' : ''; ?>>
                                        <label for="cat_<?php echo $item->id; ?>" class="custom-control-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"></label>
                                    </div>
                                </div>
                                <div class="rigt">
                                    <label for="cat_<?php echo $item->id; ?>" class="checkbox-list-item-label" onclick="window.location.href= '<?php echo generate_category_url($item) . $query_string; ?>'"><?php echo category_name($item); ?></label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <!-- SOURCE FUNDS -->
        <h4 class="title-all-categories">
                <a><?php echo trans("source_funds"); ?></a>
            </h4>
        <div class="filter-item">
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        
                            <li>
                                <div class="left">
                                    <div class="custom-control custom-checkbox">
                                        <input type="radio" class="custom-control-input" id="">
                                        <label for="" class="custom-control-label"></label>
                                    </div>
                                </div>
                                <div class="rigt">
                                    <label for="" class="checkbox-list-item-label">Bantuan Alat Pendidikan</label>
                                </div>
                            </li>
                        
                    </ul>
                </div>
            </div>
        <!-- END OF SOURCE FUNDS -->

        <?php
        if ($show_location_filter == true):
            if ($this->form_settings->product_location == 1):
                $country_id = $this->input->get('country', true);
                $state_id = $this->input->get('state', true);
                $city_id = $this->input->get('city', true); ?>
                <div class="filter-item filter-location">
                    <h4 class="title"><?php echo trans("location"); ?></h4>
                    <div class="input-group input-group-location">
                        <i class="icon-map-marker"></i>
                        <input type="text" id="input_location" class="form-control form-input" value="<?php echo get_location_input($country_id, $state_id, $city_id); ?>" placeholder="<?php echo trans("enter_location") ?>" autocomplete="off">
                    </div>
                    <div class="search-results-ajax">
                        <div class="search-results-location">
                            <div id="response_search_location"></div>
                        </div>
                    </div>
                </div>
                <?php if (!empty($country_id)): ?>
                <input type="hidden" name="country" value="<?php echo $country_id; ?>" class="input-location-filter">
            <?php endif; ?>
                <?php if (!empty($state_id)): ?>
                <input type="hidden" name="state" value="<?php echo $state_id; ?>" class="input-location-filter">
            <?php endif; ?>
                <?php if (!empty($city_id)): ?>
                <input type="hidden" name="city" value="<?php echo $city_id; ?>" class="input-location-filter">
            <?php endif; ?>
            <?php endif;
        endif; ?>

        <?php if ($this->form_settings->product_conditions == 1 && !empty($category)): ?>
            <div class="filter-item">
                <h4 class="title"><?php echo trans("condition"); ?></h4>
                <div class="filter-list-container">
                    <ul class="filter-list filter-custom-scrollbar">
                        <?php
                        $get_condition = get_filter_query_string_key_value('condition');
                        $product_conditions = get_active_product_conditions($this->selected_lang->id);
                        if (!empty($product_conditions)): ?>
                            <?php foreach ($product_conditions as $option): ?>
                                <li>
                                    <div class="left">
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" name="condition" value="<?php echo $option->option_key; ?>" id="condition_<?php echo $option->id; ?>" class="custom-control-input" onchange="this.form.submit();" <?php echo ($get_condition == $option->option_key) ? 'checked' : ''; ?>>
                                            <label for="condition_<?php echo $option->id; ?>" class="custom-control-label"></label>
                                        </div>
                                    </div>
                                    <div class="rigt">
                                        <label for="condition_<?php echo $option->id; ?>" class="checkbox-list-item-label"><?php echo $option->option_label; ?></label>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php
        if (!empty($category)):
            $category_id = $category->id;
            $custom_filters = get_custom_filters($category_id);
            if (!empty($custom_filters)):
                foreach ($custom_filters as $custom_filter): ?>
                    <div class="filter-item">
                        <h4 class="title"><?php echo $custom_filter->name; ?></h4>
                        <div class="filter-list-container">
                            <ul class="filter-list filter-custom-scrollbar">
                                <?php $options = get_custom_field_options_by_lang($custom_filter->id, $this->selected_lang->id); ?>
                                <?php if (!empty($options)):
                                    foreach ($options as $option):
                                        $get_option_value = get_filter_query_string_key_value($custom_filter->product_filter_key); ?>
                                        <li>
                                            <div class="left">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="radio" name="<?php echo $custom_filter->product_filter_key; ?>" id="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" value="<?php echo $option->field_option; ?>" class="custom-control-input" onchange="this.form.submit();" <?php echo ($get_option_value == $option->field_option) ? 'checked' : ''; ?>>
                                                    <label for="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" class="custom-control-label"></label>
                                                </div>
                                            </div>
                                            <div class="rigt">
                                                <label for="filter_option_<?php echo $custom_filter->id . '-' . $option->id ?>" class="checkbox-list-item-label"><?php echo $option->field_option; ?></label>
                                            </div>
                                        </li>
                                    <?php
                                    endforeach;
                                endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach;
            endif;
        endif; ?>
        <?php if ($this->form_settings->price == 1):
            $filter_p_max = @(float)get_filter_query_string_key_value('p_max');
            $filter_p_min = @(float)get_filter_query_string_key_value('p_min'); ?>
            <div class="filter-item">
                <h4 class="title"><?php echo trans("price"); ?></h4>
                <div class="price-filter-inputs">
                    <div class="row align-items-baseline row-price-inputs">
                        <div class="col-4 col-md-4 col-lg-5 col-price-inputs">
                            <span><?php echo trans("min"); ?></span>
                            <input type="input" name="<?php echo (get_filter_query_string_key_value('p_min')) ? 'p_min' : ''; ?>" id="price_min" value="<?php echo ($filter_p_min != 0) ? $filter_p_min : ''; ?>" class="form-control price-filter-input" placeholder="<?php echo trans("min"); ?>"
                                   onchange="this.name='p_min'"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-4 col-md-4 col-lg-5 col-price-inputs">
                            <span><?php echo trans("max"); ?></span>
                            <input type="input" name="<?php echo (get_filter_query_string_key_value('p_max')) ? 'p_max' : ''; ?>" id="price_max" value="<?php echo ($filter_p_max != 0) ? $filter_p_max : ''; ?>" class="form-control price-filter-input" placeholder="<?php echo trans("max"); ?>"
                                   onchange="this.name='p_max'"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                        <div class="col-4 col-md-4 col-lg-2 col-price-inputs text-left">
                            <button class="btn btn-sm btn-default btn-filter-price float-left"><i class="icon-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</div>
