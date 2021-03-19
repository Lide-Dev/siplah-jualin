<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div id="navMobile" class="nav-mobile">
    <div class="nav-mobile-inner">
        <div class="row">
            <div class="col-sm-12 mobile-nav-buttons">
                <?php if (is_multi_vendor_active()):
                    if ($this->auth_check): ?>
                        <a href="<?php echo generate_url("sell_now"); ?>" class="btn btn-md btn-custom btn-block"><?php echo trans("sell_now"); ?></a>
                    <?php else: ?>
                        <a href="javascript:void(0)" class="btn btn-md btn-custom btn-block close-menu-click" data-toggle="modal" data-target="#loginModal"><?php echo trans("sell_now"); ?></a>
                    <?php endif;
                endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div id="mb_category_back"></div>
                <ul class="navbar-nav">
                    <li id="mb_category_all"></li>
                    <?php $categories = get_mobile_menu_categories();
                    if (!empty($categories)):
                        foreach ($categories as $category):
                            if ($category->has_subcategory > 0): ?>
                                <li class="nav-item nav-item-category-<?php echo $category->parent_id; ?> <?php echo ($category->parent_id != 0) ? 'nav-item-hidden' : ''; ?>">
                                    <a href="javascript:void(0)" id="mb_nav_link_cat_<?php echo $category->id; ?>" class="nav-link nav-link-show-subcategories" data-category-id="<?php echo $category->id; ?>" data-category-name="<?php echo category_name($category); ?>" data-parent-id="<?php echo $category->parent_id; ?>" data-category-link="<?php echo generate_category_url($category); ?>"><?php echo category_name($category); ?>
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item nav-item-category-<?php echo $category->parent_id; ?> <?php echo ($category->parent_id != 0) ? 'nav-item-hidden' : ''; ?>">
                                    <a href="<?php echo generate_category_url($category); ?>" class="nav-link"><?php echo category_name($category); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach;
                    endif; ?>

                    <li class="nav-item nav-item-category-0"><a href="<?php echo generate_url("blog"); ?>" class="nav-link"><?php echo trans("blog"); ?></a></li>
                    <?php if ($this->auth_check): ?>
                        <li class="nav-item nav-item-category-0">
                            <a href="<?php echo generate_url("wishlist") . "/" . $this->auth_user->slug; ?>" class="nav-link">
                                <?php echo trans("wishlist"); ?>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item nav-item-category-0">
                            <a href="<?php echo generate_url("wishlist"); ?>" class="nav-link">
                                <?php echo trans("wishlist"); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($this->menu_links)): ?>
                        <?php foreach ($this->menu_links as $menu_link):
                            if ($menu_link->location == 'top_menu'):?>
                                <li class="nav-item nav-item-category-0"><a href="<?php echo generate_menu_item_url($menu_link); ?>" class="nav-link"><?php echo html_escape($menu_link->title); ?></a></li>
                            <?php endif;
                        endforeach; ?>
                    <?php endif; ?>
                    <?php if ($this->auth_check): ?>
                        <li class="dropdown profile-dropdown nav-item nav-item-category-0">
                            <a href="#" class="dropdown-toggle image-profile-drop nav-link" data-toggle="dropdown" aria-expanded="false">
                                <img src="<?php echo get_user_avatar($this->auth_user); ?>" alt="<?php echo html_escape($this->auth_user->username); ?>">
                                <?php echo html_escape($this->auth_user->username); ?> <span class="icon-arrow-down"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($this->auth_user->role == "admin"): ?>
                                    <li>
                                        <a href="<?php echo admin_url(); ?>">
                                            <i class="icon-dashboard"></i>
                                            <?php echo trans("admin_panel"); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?php echo generate_profile_url($this->auth_user->slug); ?>">
                                        <i class="icon-user"></i>
                                        <?php echo trans("view_profile"); ?>
                                    </a>
                                </li>
                                <?php if (is_sale_active()): ?>
                                    <li>
                                        <a href="<?php echo generate_url("orders"); ?>">
                                            <i class="icon-shopping-basket"></i>
                                            <?php echo trans("orders"); ?>
                                        </a>
                                    </li>
                                    <?php if (is_user_vendor()): ?>
                                        <li>
                                            <a href="<?php echo generate_url("sales"); ?>">
                                                <i class="icon-shopping-bag"></i>
                                                <?php echo trans("sales"); ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo generate_url("earnings"); ?>">
                                                <i class="icon-wallet"></i>
                                                <?php echo trans("earnings"); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (is_bidding_system_active()): ?>
                                        <li>
                                            <a href="<?php echo generate_url("quote_requests"); ?>">
                                                <i class="icon-price-tag-o"></i>
                                                <?php echo trans("quote_requests"); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($this->general_settings->digital_products_system == 1): ?>
                                        <li>
                                            <a href="<?php echo generate_url("downloads"); ?>">
                                                <i class="icon-download"></i>
                                                <?php echo trans("downloads"); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo generate_url("messages"); ?>">
                                        <i class="icon-mail"></i><?php echo trans("messages"); ?>&nbsp;<?php if ($unread_message_count > 0): ?>
                                            <span class="span-message-count"><?php echo $unread_message_count; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo generate_url("settings", "update_profile"); ?>">
                                        <i class="icon-settings"></i>
                                        <?php echo trans("settings"); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo lang_base_url(); ?>logout" class="logout">
                                        <i class="icon-logout"></i>
                                        <?php echo trans("logout"); ?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item nav-item-category-0"><a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal" class="nav-link close-menu-click"><?php echo trans("login"); ?></a></li>
                        <li class="nav-item nav-item-category-0"><a href="<?php echo generate_url("register"); ?>" class="nav-link"><?php echo trans("register"); ?></a></li>
                    <?php endif; ?>
                    <?php if (item_count($this->countries) > 1): ?>
                        <li class="nav-item nav-item-messages">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#locationModal" class="nav-link btn-modal-location close-menu-click">
                                <i class="icon-map-marker float-left" aria-hidden="true"></i>&nbsp;<?php echo trans("location"); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->general_settings->multilingual_system == 1 && count($this->languages) > 1): ?>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <?php echo trans("language"); ?>
                            </a>
                            <ul class="mobile-language-options">
                                <?php foreach ($this->languages as $language):
                                    $lang_url = base_url() . $language->short_form . "/";
                                    if ($language->id == $this->general_settings->site_lang) {
                                        $lang_url = base_url();
                                    } ?>
                                    <li>
                                        <a href="<?php echo $lang_url; ?>" class="<?php echo ($language->id == $this->selected_lang->id) ? 'selected' : ''; ?> ">
                                            <?php echo html_escape($language->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="nav-mobile-footer">
        <?php $this->load->view('partials/_social_links', ['show_rss' => true]); ?>
    </div>
</div>