<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($submain_categories)) : ?>

   <div class="featured-categories mt-3">

      <h3 class="title mb-3"><?=trans("featured_category")?></h3>

      <section class="featured-categories-collection slider">
         <?php foreach ($submain_categories as $category) : ?>
            <div class="card slide">
               <img class="img-fluid" src="<?php echo get_category_image_url($category); ?>"></img>
               <a href="<?php echo generate_category_url($category); ?>">
                  <div class="caption text-truncate">
                     <span><?php echo category_name($category); ?></span>
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      </section>

   </div>

<?php endif; ?>