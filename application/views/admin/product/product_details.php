<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('product_details'); ?></h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <?php $images = get_product_images($product->id);
                if (!empty($images)) : ?>
                    <div class="row row-product-details row-product-images">
                        <div class="col-sm-12">
                            <?php foreach ($images as $image) : ?>
                                <div class="image m-b-10">
                                    <img src="<?php echo get_product_image_url($image, 'image_small'); ?>" alt="">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('link'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <a href="<?php echo generate_product_url($product); ?>" target="_blank"><?php echo generate_product_url($product); ?></a>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('status'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php if ($product->status == 1) : ?>
                            <label class="label label-success"><?php echo trans("active"); ?></label>
                        <?php else : ?>
                            <label class="label label-danger"><?php echo trans("pending"); ?></label>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('visibility'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php if ($product->visibility == 1) : ?>
                            <label class="label label-success"><?php echo trans("visible"); ?></label>
                        <?php else : ?>
                            <label class="label label-danger"><?php echo trans("hidden"); ?></label>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('id'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->id; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('title'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->title; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('slug'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->slug; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('product_type'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?= $product->type_product_name . " - " . ($product->catalog_type == "text_book" ? "Text Book" : "Non Text Book") ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('category'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php
                        $i = 0;
                        $categories_array = get_parent_categories_array($product->category_id);
                        if (!empty($categories_array)) {
                            foreach ($categories_array as $item_array) {
                                $item_category = get_category_by_id($item_array->id);
                                if (!empty($item_category)) {
                                    if ($i != 0) {
                                        echo ", ";
                                    }
                                    echo @html_escape($item_category->name);
                                    $i++;
                                }
                            }
                        } ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('price'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php
                        if ($product->is_price_zone == 1) {
                            $i = 1;
                            foreach ($product->price as $key => $value) {
                                echo "Zona $i : " . price_formatted($value, $product->currency) . " " . $product->currency . "<br>";
                                $i++;
                            }
                        } else {
                            echo price_formatted($product->price, $product->currency) . " " . $product->currency;
                        }
                        ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('slug'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->slug; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('condition'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?= $product->product_condition ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('stock'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->stock; ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('user'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php $user = get_user($product->user_id);
                        if (!empty($user)) : ?>
                            <a href="<?php echo generate_profile_url($user->slug); ?>" target="_blank">
                                <img src="<?php echo get_user_avatar($user); ?>" alt="" style="width: 50px; height: 50px; float: left;margin-right: 10px;">
                                <strong><?php echo $user->username; ?></strong>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label">Garansi</label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->warranty; ?>
                    </div>
                </div>
                <?php if ($product->type_product_id != 3) : ?>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Lebar (cm)</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->width; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Tinggi (cm)</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->height; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Panjang (cm)</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->length; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Berat (kg)</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->weight; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Kurir Pengiriman </label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->courier_name; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Metode Pengiriman</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->shipping_method; ?>
                        </div>
                    </div>
                    <div class="row row-product-details">
                        <div class="col-md-3 col-sm-12">
                            <label class="control-label">Waktu Pengiriman</label>
                        </div>
                        <div class="col-md-9 col-sm-12 right">
                            <?php echo $product->shipping_time; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?= $product->type_product_id == 3 ?  "Jaminan Pelaksanaan Jasa" : "Jaminan Pengiriman" ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->guarantee; ?>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('reviews'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php $this->load->view('admin/includes/_review_stars', ['review' => $product->rating]); ?>
                        <span>(<?php echo $review_count; ?>)</span>
                        <style>
                            .rating {
                                float: left;
                                display: inline-block;
                                margin-right: 10px;
                            }
                        </style>
                    </div>
                </div>

                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('page_views'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php echo $product->hit; ?>
                    </div>
                </div>
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('draft'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php if ($product->is_draft == 1) : ?>
                            <label class="label label-success"><?php echo trans("yes"); ?></label>
                        <?php else : ?>
                            <label class="label label-danger"><?php echo trans("no"); ?></label>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('video_preview'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right">
                        <?php $video = $this->file_model->get_product_video($product->id);
                        if (!empty($video)) : ?>
                            <div style="width: 500px; max-width: 100%;">
                                <video controls style="width: 100%;">
                                    <source src="<?php echo get_product_video_url($video); ?>" type="video/mp4">
                                </video>
                            </div>
                        <?php endif; ?>
                    </div>
                </div> -->
                <div class="row row-product-details">
                    <div class="col-md-3 col-sm-12">
                        <label class="control-label"><?php echo trans('description'); ?></label>
                    </div>
                    <div class="col-md-9 col-sm-12 right description">
                        <?php echo $product->description; ?>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <!-- form start -->
                <?php echo form_open('product_admin_controller/approve_product'); ?>
                <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                <input type="hidden" name="redirect_url" value="<?php echo $this->agent->referrer(); ?>">

                <?php if ($product->status != 1) : ?>
                    <button type="submit" name="option" value="approve" class="btn btn-primary pull-right"><?php echo trans('approve'); ?></button>
                <?php endif; ?>
                <a href="<?php echo generate_url("sell_now", "edit_product"); ?>/<?php echo $product->id; ?>" target="_blank" class="btn btn-info pull-right m-r-5"><?php echo trans('edit'); ?></a>
                <a href="<?php echo $this->agent->referrer(); ?>" class="btn btn-danger pull-right m-r-5"><?php echo trans('back'); ?></a>
                <?php echo form_close(); ?>
                <!-- form end -->
            </div>
            <!-- /.box-footer -->

        </div>
        <!-- /.box -->
    </div>
</div>