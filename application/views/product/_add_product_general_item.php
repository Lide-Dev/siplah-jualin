<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="form-box">
    <div class="form-box-head">
        <h4 class="title">Kategori</h4>
    </div>
    <div class="form-box-body">
        <div class="row">
            <div class="col-sm-12 mb-3">
                <div class="selectdiv">
                    <select id="categories" name="category_id_0" class="form-control" onchange="get_subcategories(this.value, 0);" required>
                        <option value=""><?php echo trans('select_category'); ?></option>
                        <?php if (!empty($category)) :
                            foreach ($category as $item) : ?>
                                <option value="<?php echo html_escape($item->id); ?>"><?php echo category_name($item); ?></option>
                        <?php endforeach;
                        endif; ?>
                    </select>
                </div>
                <div id="subcategories_container"></div>
                <?php echo form_error('category_product'); ?>
            </div>
        </div>
    </div>
</div>
<!-- UPLOAD PHOTO -->
<div class="">
    <div class="col-12 m-b-30">
        <label class="control-label font-600"><?php echo trans("images"); ?></label>
        <?php $this->load->view("product/_image_upload_box"); ?>
    </div>
</div>
<?= form_open("sell-now/general-item") ?>
<!-- DETAILS PRODUCT -->
<div class="form-box">
    <div class="form-box-head">
        <h4 class="title"><?php echo trans('detail_product'); ?></h4>
    </div>
    <div class="form-box-body">
        <input type="hidden" id="hidden_category_id" value="" name="category_product">

        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("product_title"); ?></label>
                    <input type="text" name="title" class="form-control form-input" placeholder="<?php echo trans("product_title"); ?>" value="<?= set_form_value("title", true, "text", ["exact" => 5]) ?>" required maxlength="500">
                    <?php echo form_error('title'); ?>
                </div>
            </div>
        </div>
        <!-- MINIMUM ORDER & STOK -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("minimum_order"); ?></label>
                <input type="number" name="minimum_order" class="form-control form-input" placeholder="<?php echo trans("minimum_order"); ?>" value="<?= set_form_value("minimum_order", true, "number", ["max" => 10]) ?>" required max="999" min="1">
                <?php echo form_error('minimum_order'); ?>
            </div>
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("stock_product"); ?></label>
                <input type="number" name="stock" class="form-control form-input" placeholder="<?php echo trans("stock_product"); ?>" value="<?= set_form_value("stock", true, "number", ["max" => 99]) ?>" required max="999" min="1">
                <?php echo form_error('stock'); ?>
            </div>
        </div>
        <!-- END OF MINIMUM ORDER & STOK -->
        <!-- CATEGORY MADE & CODE KBKI -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("category_made_in"); ?> (Opsional)</label>
                <input type="text" name="custom_category" class="form-control form-input" placeholder="<?php echo trans("category_made_in"); ?>" maxlength="500">
                <?php echo form_error('custom_category'); ?>
            </div>
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("code_kbki"); ?></label>
                <input type="number" name="kbki" class="form-control form-input" placeholder="<?php echo trans("code_kbki"); ?>" value="<?= set_form_value("kbki", true, "number", ["exact" => 8]) ?>" max="99999999">
                <?php echo form_error('kbki'); ?>
            </div>
        </div>
        <!-- END OF CATEGORY MADE & CODE KBKI -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label">Status Ketersediaan</label>
                <select required name="availability_status" class="form-control">
                    <option value="">Pilih Salah Satu</option>
                    <option <?= set_value("availability_status") == "ready_stock" ? "selected" : "" ?> value="ready_stock">Ready Stock</option>
                    <option <?= set_value("availability_status") == "preorder" ? "selected" : "" ?> value="preorder">Preorder</option>
                </select>
                <?php echo form_error('availability_status'); ?>
            </div>
        </div>


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
            <textarea name="description" class="form-control form-input text-editor" required maxlength="1000"><?= set_form_value("description", true, "text", ["max" => 200, "min" => 50]) ?></textarea>
            <?php echo form_error('description'); ?>
        </div>

        <div class="">
            <div class="form-group col-6">
                <label class="control-label">Harga</label>
                <input type="number" name="price" class="form-control form-input" placeholder="Harga" value="<?= empty(set_value("price")) ? $book->prices : set_value("price") ?>" required min="1">
                <?php echo form_error('price'); ?>
            </div>
        </div>

        <div class="">
            <div class="form-group col-md-6">
                <label for="form_category_homemade" class="control-label"><?php echo trans("category_homemade"); ?></label>
                <label for="form_category_homemade" class="toggle-control">
                    <input id="form_category_homemade" name="category_homemade" type="checkbox">
                    <span class="control"></span>
                </label>
                <?php echo form_error('category_homemade'); ?>
            </div>
            <div class="form-group col-md-6">
                <label for="form_category_umkm" class="control-label"><?php echo trans("category_umkm"); ?></label>
                <label for="form_category_umkm" class="toggle-control">
                    <input id="form_category_umkm" name="category_umkm" type="checkbox">
                    <span class="control"></span>
                </label>
                <?php echo form_error('category_umkm'); ?>
            </div>
            <div class="form-group col-md-6">
                <label for="form_category_kemendikbud" class="control-label"><?php echo trans("category_kemendikbud"); ?></label>
                <label for="form_category_kemendikbud" class="toggle-control">
                    <input id="form_category_kemendikbud" name="category_kemendikbud" type="checkbox">
                    <span class="control"></span>
                </label>
                <?php echo form_error('category_kemendikbud'); ?>
            </div>
        </div>
        <div class="">
            <div class="form-group col-md-6">
                <label for="form_visibility" class="control-label">Tampilkan di Market?</label>
                <label for="form_visibility" class="toggle-control">
                    <input id="form_visibility" name="visibility" type="checkbox" checked="checked">
                    <span class="control"></span>
                </label>
            </div>
            <?php echo form_error('visibility'); ?>
        </div>
        <div class="">
            <div class="form-group col-md-6">
                <label for="form_vat" class="control-label">Barang Kena PPN</label>
                <label for="form_vat" class="toggle-control">
                    <input id="form_vat" name="vat" type="checkbox">
                    <span class="control"></span>
                </label>
            </div>
            <!-- <?php echo form_error('visibility'); ?> -->
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
                    <input maxlength="254" type="text" name="publisher" class="form-control form-input" placeholder="<?php echo trans("brand") . "/" . trans("publisher"); ?>" value="<?= set_form_value("publisher", true, "text", ["exact" => 20]) ?>" required>
                    <?php echo form_error('publisher'); ?>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label">Garansi (Opsional)</label>
                    <input maxlength="254" type="text" name="warranty" class="form-control form-input" placeholder="Garansi" value="<?= set_form_value("warranty", true, "text", ["exact" => 20]) ?>" required>
                    <?php echo form_error('warranty'); ?>
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("condition"); ?></label>
                    <input required maxlength="254" type="text" name="condition" class="form-control form-input" placeholder="<?php echo trans("condition"); ?>" value="<?= set_form_value("kbki", true, "select", ["value" => ["Baru", "Bekas"]]) ?>">
                    <?php echo form_error('condition'); ?>
                </div>
            </div>
        </div>
        <!-- SKU -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("sku"); ?>&nbsp;(<?php echo trans("product_code"); ?>)</label>
                <input required maxlength="254" type="text" name="sku" class="form-control form-input" placeholder="<?php echo trans("sku"); ?>" value="<?= set_form_value("sku", true, "text", ["exact" => 10, "add_first" => "SKU-"]) ?>">
                <?php echo form_error('sku'); ?>
            </div>
        </div>
        <!-- END OF SKU -->

        <div class="row">
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("width"); ?> ( CM )</label>
                    <input required type="number" name="width" class="form-control form-input" placeholder="<?php echo trans("width"); ?>" value="<?= set_form_value("width", true, "number", ["min" => 1, "max" => 100]) ?>" required>
                    <?php echo form_error('width'); ?>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("length") ?> ( CM )</label>
                    <input required type="number" name="length" class="form-control form-input" placeholder="<?php echo trans("length")  ?>" value="<?= set_form_value("length", true, "number", ["min" => 1, "max" => 100]) ?>" required>
                    <?php echo form_error('length'); ?>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("height") ?> ( CM )</label>
                    <input required type="number" name="height" class="form-control form-input" placeholder="<?php echo trans("height") ?>" value="<?= set_form_value("height", true, "number", ["min" => 1, "max" => 100]) ?>" required>
                    <?php echo form_error('height'); ?>
                </div>
            </div>
            <div class="col-md-3 col-4">
                <div class="form-group">
                    <label class="control-label"><?php echo trans("weight") ?> ( Gram )</label>
                    <input required type="number" name="weight" class="form-control form-input" placeholder="<?php echo trans("weight") ?>" value="<?= set_form_value("weight", true, "number", ["min" => 1, "max" => 100]) ?>" required>
                    <?php echo form_error('weight'); ?>
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
            <div class="form-group col-6">
                <label class="control-label">Kurir Pengiriman</label>
                <select required name="courier" class="form-control">
                    <option value="">Pilih Salah Satu</option>
                    <?php foreach ($couriers as $courier) : ?>
                        <option <?= set_value("courier") == $courier->id ? "selected" : "" ?> value="<?= $courier->id ?>"><?= $courier->name ?></option>
                    <?php endforeach; ?>
                </select>
                <?php echo form_error('courier'); ?>
            </div>
            <div class="form-group col-6">
                <label class="control-label">Metode Pengiriman</label>
                <input required maxlength="254" type="text" name="delivery_method" class="form-control form-input" placeholder="Metode Pengiriman" value="<?= set_form_value("delivery_method", true, "select", ["value" => ["Reguler", "Express"]]) ?>">
                <?php echo form_error('delivery_method'); ?>
            </div>
        </div>


        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("delivery_time"); ?></label>
                <input required maxlength="254" type="text" name="delivery_time" class="form-control form-input" placeholder="<?php echo trans("delivery_time"); ?>" value="<?= set_form_value("delivery_time", true, "select", ["value" => ["1-2 Hari Kerja", "2-3 Hari Kerja"]]) ?>">
                <?php echo form_error('delivery_time'); ?>
            </div>
            <!-- <div class="form-group col-3">
                    <label class="control-label"><?php echo trans("shipping_way"); ?></label>
                    <input type="text" name="" class="form-control form-input" placeholder="<?php echo trans("shipping_way"); ?>">
                </div> -->
        </div>
        <!-- ASSURANCE -->
        <div class="row">
            <div class="form-group col-6">
                <label class="control-label"><?php echo trans("delivery_assurance"); ?> (Opsional)</label>
                <input maxlength="254" type="text" name="delivery_assurance" class="form-control form-input" placeholder="<?php echo trans("delivery_assurance"); ?>">
                <?php echo form_error('delivery_assurance'); ?>
            </div>
        </div>
        <!-- END OF ASSURANCE -->
        <!-- END OF DELIVERY TIME & SHIPPING WAY -->
    </div>
</div>

<div class="form-group">
    <button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("save_and_continue"); ?></button>
</div>
<!-- END OF DETAILS PRODUCT -->
<?= form_close() ?>