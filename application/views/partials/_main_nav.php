<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($this->general_settings->selected_navigation == 1): ?>
    <div class="container">
        <div class="navbar navbar-light navbar-expand">
            <ul class="nav navbar-nav mega-menu">
                <?php $limit = $this->general_settings->menu_limit;
                $count = 1;
                if (!empty($this->parent_categories)):
                    foreach ($this->parent_categories as $category):
                        if ($count <= $limit):?>
                            <li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
                                <a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
                                    <?php echo category_name($category); ?>
                                </a>
                                <?php $subcategories = get_subcategories($this->categories, $category->id);
                                if (!empty($subcategories)): ?>
                                    <div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu">
                                        <div class="row">
                                            <div class="col-8 menu-subcategories col-category-links">
                                                <div class="card-columns">
                                                    <?php foreach ($subcategories as $subcategory): ?>
                                                        <div class="card">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <a href="<?php echo generate_category_url($subcategory); ?>" class="second-category"><?php echo category_name($subcategory); ?></a>
                                                                    <?php
                                                                    $third_categories = get_subcategories($this->categories, $subcategory->id);
                                                                    if (!empty($third_categories)):
                                                                        $display_limit = 1; ?>
                                                                        <ul>
                                                                            <?php foreach ($third_categories as $item):
                                                                                if ($display_limit <= $this->menu_subcategory_display_limit):?>
                                                                                    <li>
                                                                                        <a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
                                                                                    </li>
                                                                                <?php endif;
                                                                                $display_limit++;
                                                                            endforeach; ?>
                                                                            <?php if ($display_limit > $this->menu_subcategory_display_limit): ?>
                                                                                <li>
                                                                                    <a href="<?php echo generate_category_url($subcategory); ?>" class="link-view-all"><?php echo trans("show_all"); ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        </ul>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                            <div class="col-4 col-category-images">
                                                <?php if (!empty($category->image) && $category->show_image_on_navigation == 1): ?>
                                                    <div class="nav-category-image">
                                                        <a href="<?php echo generate_category_url($category); ?>">
                                                            <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_category_image_url($category); ?>" alt="category" class="lazyload img-fluid">
                                                            <span><?php echo character_limiter(category_name($category), 20, '..'); ?></span>
                                                        </a>
                                                    </div>
                                                <?php endif;
                                                foreach ($subcategories as $subcategory):
                                                    if (!empty($subcategory->image) && $subcategory->show_image_on_navigation == 1):?>
                                                        <div class="nav-category-image">
                                                            <a href="<?php echo generate_category_url($subcategory); ?>">
                                                                <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_category_image_url($subcategory); ?>" alt="<?php echo category_name($subcategory); ?>" class="lazyload img-fluid">
                                                                <span><?php echo character_limiter(category_name($subcategory), 20, '..'); ?></span>
                                                            </a>
                                                        </div>
                                                    <?php endif;
                                                endforeach;
                                                foreach ($subcategories as $subcategory):
                                                    $third_categories = get_subcategories($this->categories, $subcategory->id);
                                                    if (!empty($third_categories)):
                                                        foreach ($third_categories as $third_category):
                                                            if (!empty($third_category->image) && $third_category->show_image_on_navigation == 1):?>
                                                                <div class="nav-category-image">
                                                                    <a href="<?php echo generate_category_url($third_category); ?>">
                                                                        <img src="<?php echo base_url() . IMG_BG_PRODUCT_SMALL; ?>" data-src="<?php echo get_category_image_url($third_category); ?>" alt="<?php echo category_name($third_category); ?>" class="lazyload img-fluid">
                                                                        <span><?php echo category_name($third_category); ?></span>
                                                                    </a>
                                                                </div>
                                                            <?php endif;
                                                        endforeach;
                                                    endif;
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <?php $count++;
                        endif;
                    endforeach;

                    if (item_count($this->parent_categories) > $limit): ?>
                        <li class="nav-item dropdown menu-li-more">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo trans("more"); ?></a>
                            <div class="dropdown-menu dropdown-menu-more-items">
                                <?php
                                $count = 1;
                                if (!empty($this->parent_categories)):
                                    foreach ($this->parent_categories as $category):
                                        if ($count > $limit):?>
                                            <a href="<?php echo generate_category_url($category); ?>" class="dropdown-item">
                                                <?php echo category_name($category); ?>
                                            </a>
                                        <?php
                                        endif;
                                        $count++;
                                    endforeach;
                                endif; ?>
                            </div>
                        </li>
                    <?php endif;
                endif; ?>
            </ul>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <div class="navbar navbar-light navbar-expand">
            <ul class="nav navbar-nav mega-menu">
                <?php
                $limit = $this->general_settings->menu_limit;
                $menu_item_count = 1;
                if (!empty($this->parent_categories)):
                    foreach ($this->parent_categories as $category):
                        if ($menu_item_count <= $limit):?>
                            <li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
                                <a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
                                    <?php echo category_name($category); ?>
                                </a>
                                <?php $subcategories = get_subcategories($this->categories, $category->id);
                                if (!empty($subcategories)):?>
                                    <div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu dropdown-menu-large">
                                        <div class="row">

                                            <div class="col-4 left">
                                                <?php
                                                $count = 0;
                                                foreach ($subcategories as $subcategory): ?>
                                                    <div class="large-menu-item <?php echo ($count == 0) ? 'large-menu-item-first active' : ''; ?>" data-subcategory-id="<?php echo $subcategory->id; ?>">
                                                        <a href="<?php echo generate_category_url($subcategory); ?>" class="second-category">
                                                            <?php echo category_name($subcategory); ?>
                                                            <i class="icon-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                    <?php
                                                    $count++;
                                                endforeach; ?>
                                            </div>

                                            <div class="col-8 right">
                                                <?php
                                                $count = 0;
                                                foreach ($subcategories as $subcategory): ?>
                                                    <div id="large_menu_content_<?php echo $subcategory->id; ?>" class="large-menu-content <?php echo ($count == 0) ? 'large-menu-content-first active' : ''; ?>">
                                                        <?php $third_categories = get_subcategories($this->categories, $subcategory->id);
                                                        if (!empty($third_categories)): ?>
                                                            <div class="row">
                                                                <?php foreach ($third_categories as $item): ?>
                                                                    <div class="col-4 item-large-menu-content">
                                                                        <a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                    $count++;
                                                endforeach; ?>
                                            </div>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            </li>
                            <?php $menu_item_count++;
                        endif;
                    endforeach;

                    if (item_count($this->parent_categories) > $limit): ?>
                        <li class="nav-item dropdown menu-li-more">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo trans("more"); ?></a>
                            <div class="dropdown-menu dropdown-menu-more-items">
                                <?php
                                $menu_item_count = 1;
                                if (!empty($this->parent_categories)):
                                    foreach ($this->parent_categories as $category):
                                        if ($menu_item_count > $limit): ?>
                                            <a href="<?php echo generate_category_url($category); ?>" class="dropdown-item">
                                                <?php echo category_name($category); ?>
                                            </a>
                                        <?php endif;
                                        $menu_item_count++;
                                    endforeach;
                                endif; ?>
                            </div>
                        </li>
                    <?php endif;
                endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
