<?php if (isset($_SESSION["ap_selected_book_id"])) : ?>
    <div class="form-box">
        <!-- <div class="form-box-head">
            <h4 class="title">Buku Yang Dipilih</h4>
        </div> -->
        <div class="form-box-body">
            <?php $this->load->view("product/_catalog_book_detail", ["book" => $book, 'cancel_option' => true]) ?>
        </div>
    </div>

    <!-- UPLOAD PHOTO -->
    <div class="row">
        <div class="col-12 m-b-30">
            <label class="control-label font-600"><?php echo trans("images"); ?></label>
            <?php $this->load->view("product/_image_upload_box"); ?>
        </div>
    </div>

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
<?php else : ?>
    <!-- CATEGORY PRODUCT -->

    <div class="row">
        <div class="col-sm-12">
            <div class="form-box">
                <div class="form-box-head">
                    <h4 class="title">Pilih Katalog Buku</h4>
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
                        </div>
                        <div class="col-sm-12 mb-3">
                            <?= form_open(base_url("sell-now/specified_item"), ["method" => "GET"]) ?>

                            <div class="row form-inline">
                                <div class="container form-group">
                                    <!-- <label class="control-label">Pencarian Katalog Buku</label> -->
                                    <input type="text" name="search" class="form-control form-input" placeholder="Cari Buku">
                                    <button class="ml-2 btn btn-custom btn-sell-now">Cari</button>
                                </div>
                            </div>
                            <input type="hidden" id="hidden_category_id" value="" name="filter_category">
                            <div class="row">
                                <div class="form-group col-4">
                                    <label class="control-label">Klasifikasi</label>
                                    <select name="filter_classification" class="form-control">
                                        <option value="">Semua</option>
                                        <?php foreach ($classification as $value) : ?>
                                            <option value="<?= $value->name ?>" <?= $value->name == xss_clean($_GET["filter_classification"]) ? "selected" : "" ?>><?= $value->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label class="control-label">Tingkat Sekolah</label>
                                    <select name="filter_school_level" class="form-control">
                                        <option value="">Semua</option>
                                        <?php foreach ($school_level as $value) : ?>
                                            <option value="<?= $value->name ?>" <?= $value->name == xss_clean($_GET["filter_school_level"]) ? "selected" : "" ?>><?= $value->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group col-4">
                                    <label class="control-label">Kelas</label>
                                    <select name="filter_school_class" class="form-control">
                                        <option value="">Semua</option>
                                        <?php foreach ($school_class as $value) : ?>
                                            <option value="<?= $value->name ?>" <?= $value->name == xss_clean($_GET["filter_school_class"]) ? "selected" : "" ?>><?= $value->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <?php form_close() ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php $this->load->view("product/_catalog_book") ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF CATEGORY PRODUCT -->

<?php endif; ?>