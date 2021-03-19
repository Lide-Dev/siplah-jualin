<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="<?php echo ($this->general_settings->slider_type == "boxed") ? "container container-boxed-slider" : "container-fluid"; ?>">
    <div class="row">
        <div class="slider-container">
            <div id="main-slider" class="main-slider">
                <?php foreach ($slider_items as $item): ?>
                    <div class="item lazyload" data-bg="<?php echo base_url() . $item->image; ?>">
                        <a href="<?php echo html_escape($item->link); ?>">
                            <div class="container">
                                <div class="row row-slider-caption align-items-center">
                                    <div class="col-12">
                                        <div class="caption">
                                            <?php if (!empty($item->title)): ?>
                                                <h2 class="title" data-animation="<?php echo $item->animation_title; ?>" data-delay="0.1s" style="color: <?php echo $item->text_color; ?>"><?php echo html_escape($item->title); ?></h2>
                                            <?php endif;
                                            if (!empty($item->description)): ?>
                                                <p class="description" data-animation="<?php echo $item->animation_description; ?>" data-delay="0.5s" style="color: <?php echo $item->text_color; ?>"><?php echo html_escape($item->description); ?></p>
                                            <?php endif;
                                            if (!empty($item->button_text)): ?>
                                                <button class="btn btn-slider" data-animation="<?php echo $item->animation_button; ?>" data-delay="0.9s" style="background-color: <?php echo $item->button_color; ?>;border-color: <?php echo $item->button_color; ?>;color: <?php echo $item->button_text_color; ?>"><?php echo html_escape($item->button_text); ?></button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="main-slider-nav" class="main-slider-nav">
                <button class="prev"><i class="icon-arrow-left"></i></button>
                <button class="next"><i class="icon-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>
