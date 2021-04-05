<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (!empty($featured_categories)) : ?>

   <div class="container">
      <h3 class="title mb-5">Kategori Belanja</h3>
      <section class="customer-logos slider">
         <div class="slide"><img src="https://images.unsplash.com/photo-1586952518485-11b180e92764?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=522&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1471970471555-19d4b113e9ed?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1600658747056-eb00845297a5?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
         <div class="slide"><img src="https://images.unsplash.com/photo-1577375729078-820d5283031c?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80"></div>
      </section>
   </div>

   <div class="featured-categories">
      <div class="container">
         <?php foreach ($featured_categories as $category) : ?>
            <div class="card lazyload" data-bg="<?php echo get_category_image_url($category); ?>">
               <a href="<?php echo generate_category_url($category); ?>">
                  <div class="caption text-truncate">
                     <span><?php echo category_name($category); ?></span>
                  </div>
               </a>
            </div>
         <?php endforeach; ?>
      </div>
   </div>


<?php endif; ?>