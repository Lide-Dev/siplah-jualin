<!-- CATEGORY PRODUCT -->
<div class="row">
    <div class="col-sm-12">
        <div class="form-box">
            <div class="form-box-head">
                <h4 class="title"><?php echo trans('category_add_product'); ?></h4>
            </div>
            <div class="form-box-body">
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <div class="selectdiv">
                            <select id="categories" name="category_id_0" class="form-control" onchange="get_subcategories(this.value, 0);" required>
                                <option value=""><?php echo trans('select_category'); ?></option>
                                <?php if (!empty($this->parent_categories)) :
                                    foreach ($this->parent_categories as $item) : ?>
                                        <option value="<?php echo html_escape($item->id); ?>"><?php echo category_name($item); ?></option>
                                <?php endforeach;
                                endif; ?>
                            </select>
                        </div>
                        <div id="subcategories_container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- UPLOAD PHOTO -->
<div class="row">
    <div class="col-12 m-b-30">
        <label class="control-label font-600"><?php echo trans("images"); ?></label>
        <?php $this->load->view("product/_image_upload_box"); ?>
    </div>
</div>
<!-- END OF CATEGORY PRODUCT -->
<!-- <?php if ($this->general_settings->physical_products_system == 1 && $this->general_settings->digital_products_system == 0) : ?>
                                        <input type="hidden" name="product_type" value="physical">
                                    <?php elseif ($this->general_settings->physical_products_system == 0 && $this->general_settings->digital_products_system == 1) : ?>
                                        <input type="hidden" name="product_type" value="digital">
                                    <?php else : ?>
                                        <div class="form-box">
                                            <div class="form-box-head">
                                                <h4 class="title"><?php echo trans('product_type'); ?></h4>
                                            </div>
                                            <div class="form-box-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <?php if ($this->general_settings->physical_products_system == 1) : ?>
                                                            <div class="col-12 col-sm-6 col-option">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="product_type" value="physical" id="product_type_1" class="custom-control-input" checked required>
                                                                    <label for="product_type_1" class="custom-control-label"><?php echo trans('physical'); ?></label>
                                                                    <p class="form-element-exp"><?php echo trans('physical_exp'); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($this->general_settings->digital_products_system == 1) : ?>
                                                            <div class="col-12 col-sm-6 col-option">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="product_type" value="digital" id="product_type_2" class="custom-control-input" <?php echo ($this->general_settings->physical_products_system != 1) ? 'checked' : ''; ?> required>
                                                                    <label for="product_type_2" class="custom-control-label"><?php echo trans('digital'); ?></label>
                                                                    <p class="form-element-exp"><?php echo trans('digital_exp'); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?> -->

<!-- <?php if ($active_product_system_array['active_system_count'] > 1) : ?>
                                        <div class="form-box">
                                            <div class="form-box-head">
                                                <h4 class="title"><?php echo trans('listing_type'); ?></h4>
                                            </div>
                                            <div class="form-box-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <?php if ($this->general_settings->marketplace_system == 1) : ?>
                                                            <div class="col-12 col-sm-6 col-option listing_sell_on_site">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="listing_type" value="sell_on_site" id="listing_type_1" class="custom-control-input" checked required>
                                                                    <label for="listing_type_1" class="custom-control-label"><?php echo trans('add_product_for_sale'); ?></label>
                                                                    <p class="form-element-exp"><?php echo trans('add_product_for_sale_exp'); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($this->general_settings->classified_ads_system == 1) : ?>
                                                            <div class="col-12 col-sm-6 col-option listing_ordinary_listing">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="listing_type" value="ordinary_listing" id="listing_type_2" class="custom-control-input" <?php echo ($this->general_settings->marketplace_system != 1) ? 'checked' : ''; ?> required>
                                                                    <label for="listing_type_2" class="custom-control-label"><?php echo trans('add_product_services_listing'); ?></label>
                                                                    <p class="form-element-exp"><?php echo trans('add_product_services_listing_exp'); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ($this->general_settings->bidding_system == 1) : ?>
                                                            <div class="col-12 col-sm-6 col-option listing_bidding">
                                                                <div class="custom-control custom-radio">
                                                                    <input type="radio" name="listing_type" value="bidding" id="listing_type_3" class="custom-control-input" required>
                                                                    <label for="listing_type_3" class="custom-control-label"><?php echo trans('add_product_get_price_requests'); ?></label>
                                                                    <p class="form-element-exp"><?php echo trans('add_product_get_price_requests_exp'); ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <input type="hidden" name="listing_type" value="<?php echo $active_product_system_array['active_system_value']; ?>">
                                    <?php endif; ?> -->

<!-- END OF PRODUCT TYPE -->
<!-- DETAILS PRODUCT -->
<div class="form-box">
    <div class="form-box-head">
        <h4 class="title"><?php echo trans('detail_product'); ?></h4>
    </div>
    <div class="form-box-body">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("product_title"); ?></label>
                    <input type="text" name="title" class="form-control form-input" placeholder="<?php echo trans("product_title"); ?>" required>
                </div>
            </div>
        </div>
        <!-- MINIMUM ORDER & STOK -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("minimum_order"); ?></label>
                <input type="number" name="" class="form-control form-input" placeholder="<?php echo trans("minimum_order"); ?>">
            </div>
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("stock_product"); ?></label>
                <input type="number" name="" class="form-control form-input" placeholder="<?php echo trans("stock_product"); ?>">
            </div>
        </div>
        <!-- END OF MINIMUM ORDER & STOK -->
        <!-- CATEGORY MADE & CODE KBKI -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("category_made_in"); ?></label>
                <input type="text" name="custom_category" class="form-control form-input" placeholder="<?php echo trans("category_made_in"); ?>">
            </div>
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("code_kbki"); ?></label>
                <input type="number" name="" class="form-control form-input" placeholder="<?php echo trans("code_kbki"); ?>">
            </div>
        </div>
        <!-- END OF CATEGORY MADE & CODE KBKI -->
        <!-- ASSURANCE -->
        <?php if (!empty($type_product) && $type_product === "service") : ?>
            <div class="row">
                <div class="form-group col-6">
                    <label class="control-label"><?php echo trans("service_assurance"); ?></label>
                    <input type="file" name="service_assurance" class="form-control form-input" placeholder="<?php echo trans("service_assurance"); ?>">
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="form-group col-6">
                    <label class="control-label"><?php echo trans("delivery_assurance"); ?></label>
                    <input type="file" name="delivery_assurance" class="form-control form-input" placeholder="<?php echo trans("delivery_assurance"); ?>">
                </div>
            </div>
        <?php endif; ?>

        <!-- END OF ASSURANCE -->

        <div class="form-group">
            <label class="control-label"><?php echo trans("description"); ?></label>
            <!-- <div class="row">
                                                    <div class="col-sm-12 m-b-5">
                                                        <button type="button" class="btn btn-sm btn-secondary color-white btn_ck_add_image m-b-5"><i class="icon-image"></i><?php echo trans("add_image"); ?></button>
                                                        <button type="button" class="btn btn-sm btn-info color-white btn_ck_add_video m-b-5"><i class="icon-image"></i><?php echo trans("add_video"); ?></button>
                                                        <button type="button" class="btn btn-sm btn-warning color-white btn_ck_add_iframe m-b-5"><i class="icon-image"></i><?php echo trans("add_iframe"); ?></button>
                                                    </div>
                                                </div>
                                                <textarea name="description" id="ckEditor" class="text-editor"></textarea> -->
            <textarea name="description" class="form-control form-input text-editor"></textarea>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="form_category_homemade" class="control-label"><?php echo trans("category_homemade"); ?></label>
                <label for="form_category_homemade" class="toggle-control">
                    <input id="form_category_homemade" type="checkbox" checked="checked">
                    <span class="control"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="form_category_umkm" class="control-label"><?php echo trans("category_umkm"); ?></label>
                <label for="form_category_umkm" class="toggle-control">
                    <input id="form_category_umkm" type="checkbox" checked="checked">
                    <span class="control"></span>
                </label>
            </div>
            <div class="form-group col-md-6">
                <label for="form_category_kemendikbud" class="control-label"><?php echo trans("category_kemendikbud"); ?></label>
                <label for="form_category_kemendikbud" class="toggle-control">
                    <input id="form_category_kemendikbud" type="checkbox" checked="checked">
                    <span class="control"></span>
                </label>
            </div>
        </div>


    </div>
</div>

<!-- SPECIFICATION PRODUCT -->
<div class="form-box">
    <div class="form-box-head">
        <h4 class="title"><?php echo trans('specification_product'); ?></h4>
    </div>
    <div class="form-box-body">

        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("brand") . "/" . trans("publisher"); ?></label>
                    <input type="text" name="title" class="form-control form-input" placeholder="<?php echo trans("brand") . "/" . trans("publisher"); ?>" required>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("condition"); ?></label>
                    <input type="text" name="sku" class="form-control form-input" placeholder="<?php echo trans("condition"); ?>">
                </div>
            </div>
        </div>
        <!-- SKU -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("sku"); ?>&nbsp;(<?php echo trans("product_code"); ?>)</label>
                <input type="text" name="sku" class="form-control form-input" placeholder="<?php echo trans("sku"); ?>">
            </div>
        </div>
        <!-- END OF SKU -->

        <div class="row">
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("width"); ?> ( CM )</label>
                    <input type="number" name="width" class="form-control form-input" placeholder="<?php echo trans("width"); ?>" required>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("length") ?> ( CM )</label>
                    <input type="number" name="length" class="form-control form-input" placeholder="<?php echo trans("length")  ?>" required>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("height") ?> ( CM )</label>
                    <input type="number" name="height" class="form-control form-input" placeholder="<?php echo trans("height") ?>" required>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("weight") ?> ( KG )</label>
                    <input type="number" name="weight" class="form-control form-input" placeholder="<?php echo trans("weight") ?>" required>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END OF SPECIFICATION PRODUCT -->

<div class="form-box">
    <div class="form-box-head">
        <h4 class="title"><?php echo trans('delivery_setting'); ?></h4>
    </div>
    <div class="form-box-body">
        <!-- DELIVERY TIME & SHIPPING WAY -->

        <div class="row">
            <div class="form-group col-3">
                <label class="control-label"><?php echo trans("delivery_time"); ?></label>
                <input type="text" name="" class="form-control form-input" placeholder="<?php echo trans("delivery_time"); ?>">
            </div>
            <div class="form-group col-3">
                <label class="control-label"><?php echo trans("shipping_way"); ?></label>
                <input type="text" name="" class="form-control form-input" placeholder="<?php echo trans("shipping_way"); ?>">
            </div>
        </div>
        <!-- END OF DELIVERY TIME & SHIPPING WAY -->
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("save_and_continue"); ?></button>
</div>
<!-- END OF DETAILS PRODUCT -->