<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="blog-item">
    <div class="blog-item-img">
        <a href="<?php echo generate_url("blog") . "/" . $item->category_slug . "/" . $item->slug; ?>">
            <?php if (!empty($blog_slider)): ?>
                <img src="<?php echo base_url() . IMG_BG_BLOG_SMALL; ?>" data-lazy="<?php echo get_blog_image_url($item, 'image_small'); ?>" alt="<?php echo html_escape($item->title); ?>" class="img-fluid"/>
            <?php else: ?>
                <img src="<?php echo base_url() . IMG_BG_BLOG_SMALL; ?>" data-src="<?php echo get_blog_image_url($item, 'image_small'); ?>" alt="<?php echo html_escape($item->title); ?>" class="img-fluid lazyload"/>
            <?php endif; ?>
        </a>
    </div>
    <h3 class="blog-post-title">
        <a href="<?php echo generate_url("blog") . "/" . $item->category_slug . "/" . $item->slug; ?>">
            <?php echo html_escape(character_limiter($item->title, 56, '...')); ?>
        </a>
    </h3>
    <div class="blog-post-meta">
        <a href="<?php echo generate_url("blog") . "/" . $item->category_slug; ?>">
            <i class="icon-folder"></i><?php echo html_escape($item->category_name); ?>
        </a>
        <span><i class="icon-clock"></i><?php echo time_ago($item->created_at); ?></span>
    </div>
    <div class="blog-post-description">
        <?php echo html_escape(character_limiter($item->summary, 100, '...')); ?>
    </div>
</div>
