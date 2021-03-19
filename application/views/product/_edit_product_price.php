<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($product->listing_type == 'sell_on_site'): ?>
    <div class="form-box">
        <div class="form-box-head">
            <h4 class="title"><?php echo trans('product_price'); ?></h4>
        </div>
        <div class="form-box-body">
            <div id="price_input_container" class="form-group">
                <div class="row">
                    <div class="col-12 col-sm-4 m-b-sm-15">
                        <label class="control-label"><?php echo trans("price"); ?></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text input-group-text-currency" id="basic-addon1"><?php echo get_currency($this->payment_settings->default_product_currency); ?></span>
                                <input type="hidden" name="currency" value="<?php echo $this->payment_settings->default_product_currency; ?>">
                            </div>
                            <input type="text" name="price" id="product_price_input" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" value="<?php echo ($product->price != 0) ? get_price($product->price, 'input') : ''; ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 m-b-sm-15">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <label class="control-label"><?php echo trans("discount_rate"); ?></label>
                                <div id="discount_input_container" class="<?php echo ($product->discount_rate == 0) ? 'display-none' : ''; ?>">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text input-group-text-currency" id="basic-addon-discount">%</span>
                                            <input type="hidden" name="currency" value="<?php echo $this->payment_settings->default_product_currency; ?>">
                                        </div>
                                        <input type="number" name="discount_rate" id="input_discount_rate" aria-describedby="basic-addon-discount" class="form-control form-input" value="<?php echo $product->discount_rate; ?>" min="0" max="99">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 m-t-10">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="discount_rate" id="checkbox_discount_rate" <?php echo ($product->discount_rate == 0) ? 'checked' : ''; ?>>
                                    <label for="checkbox_discount_rate" class="custom-control-label"><?php echo trans("no_discount"); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($this->general_settings->vat_status == 1): ?>
                        <div class="col-12 col-sm-4">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <label class="control-label"><?php echo trans("vat"); ?><small>&nbsp;(<?php echo trans("vat_exp"); ?>)</small></label>
                                    <div id="vat_input_container" class="<?php echo ($product->vat_rate == 0) ? 'display-none' : ''; ?>">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text input-group-text-currency" id="basic-addon-vat">%</span>
                                                <input type="hidden" name="currency" value="<?php echo $this->payment_settings->default_product_currency; ?>">
                                            </div>
                                            <input type="number" name="vat_rate" id="input_vat_rate" aria-describedby="basic-addon-vat" class="form-control form-input" value="<?php echo $product->vat_rate; ?>" min="0" max="100">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 m-t-10">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="vat_included" id="checkbox_vat_included" <?php echo ($product->vat_rate == 0) ? 'checked' : ''; ?>>
                                        <label for="checkbox_vat_included" class="custom-control-label"><?php echo trans("vat_included"); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-12 m-t-30">
                        <p class="calculated-price">
                            <strong><?php echo trans("calculated_price"); ?> (<?php echo get_currency($this->payment_settings->default_product_currency); ?>):&nbsp;&nbsp;</strong>
                            <i id="calculated_amount" class="earned-price">
                                <?php $earned_amount = calculate_product_price($product->price, $product->discount_rate);
                                $earned_amount = number_format($earned_amount, 2, '.', '');
                                echo get_price($earned_amount, 'input'); ?>
                            </i>
                        </p>
                        <p class="calculated-price calculated_vat_container <?php echo ($product->vat_rate == 0) ? 'display-none' : ''; ?>">
                            <strong><?php echo trans("vat"); ?> (<?php echo get_currency($this->payment_settings->default_product_currency); ?>):&nbsp;&nbsp;</strong>
                            <i id="vat_amount" class="earned-price">
                                <?php $earned_amount = calculate_product_vat($product);
                                $earned_amount = number_format($earned_amount, 2, '.', '');
                                echo get_price($earned_amount, 'input'); ?>
                            </i>
                        </p>
                        <p class="calculated-price">
                            <strong><?php echo trans("you_will_earn"); ?> (<?php echo get_currency($this->payment_settings->default_product_currency); ?>):&nbsp;&nbsp;</strong>
                            <i id="earned_amount" class="earned-price">
                                <?php $earned_amount = calculate_earned_amount($product);
                                $earned_amount = number_format($earned_amount, 2, '.', '');
                                echo get_price($earned_amount, 'input'); ?>
                            </i>
                            &nbsp;&nbsp;&nbsp;
                            <small> (<?php echo trans("commission_rate"); ?>:&nbsp;&nbsp;<?php echo $this->general_settings->commission_rate; ?>%)</small>
                        </p>
                    </div>
                </div>
            </div>
            <?php if ($product->product_type == 'digital'): ?>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="is_free_product" id="checkbox_free_product" <?php echo ($product->is_free_product == 1) ? 'checked' : ''; ?>>
                        <label for="checkbox_free_product" class="custom-control-label"><?php echo trans("free_product"); ?></label>
                    </div>
                </div>
                <script>
                    $(document).on('click', '#checkbox_free_product', function () {
                        if ($(this).is(':checked')) {
                            $('#price_input_container').hide();
                        } else {
                            $('#price_input_container').show();
                        }
                    });
                </script>
            <?php if ($product->is_free_product == 1): ?>
                <style>
                    #price_input_container {
                        display: none;;
                    }
                </style>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

<?php elseif ($product->listing_type == 'ordinary_listing'):
    if ($this->form_settings->price == 1): ?>
        <div class="form-box">
            <div class="form-box-head">
                <h4 class="title"><?php echo trans('price'); ?></h4>
            </div>
            <div class="form-box-body">
                <div class="form-group">
                    <div class="row">
                        <?php if ($this->payment_settings->allow_all_currencies_for_classied == 1): ?>
                            <div class="col-12 col-sm-4 m-b-sm-15">
                                <div class="selectdiv">
                                    <select name="currency" class="form-control" required>
                                        <?php if (!empty($this->currencies)):
                                            foreach ($this->currencies as $key => $value):
                                                if ($key == $product->currency):?>
                                                    <option value="<?php echo $key; ?>" selected><?php echo $value["name"] . " (" . $value["hex"] . ")"; ?></option>
                                                <?php else: ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $value["name"] . " (" . $value["hex"] . ")"; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4 m-b-sm-15">
                                <input type="text" name="price" class="form-control form-input price-input validate-price-input" value="<?php echo ($product->price != 0) ? get_price($product->price, 'input') : ''; ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" <?php echo ($this->form_settings->price_required == 1) ? 'required' : ''; ?>>
                            </div>
                        <?php else: ?>
                            <div class="col-12 col-sm-6 m-b-sm-15">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text input-group-text-currency" id="basic-addon2"><?php echo get_currency($this->payment_settings->default_product_currency); ?></span>
                                        <input type="hidden" name="currency" value="<?php echo $this->payment_settings->default_product_currency; ?>">
                                    </div>
                                    <input type="text" name="price" id="product_price_input" aria-describedby="basic-addon2" class="form-control form-input price-input validate-price-input" value="<?php echo ($product->price != 0) ? get_price($product->price, 'input') : ''; ?>" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" <?php echo ($this->form_settings->price_required == 1) ? 'required' : ''; ?>>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php elseif ($product->listing_type == 'bidding'): ?>
    <input type="hidden" name="currency" value="<?php echo $this->payment_settings->default_product_currency; ?>">
<?php endif; ?>

<?php if ($product->listing_type == 'sell_on_site'): ?>
    <script>
        //calculate product earned value
        var thousands_separator = '<?php echo $this->thousands_separator; ?>';
        var commission_rate = '<?php echo $this->general_settings->commission_rate; ?>';
        $(document).on("input keyup paste change", "#product_price_input", function () {
            calculate_earn_amount();
        });
        $(document).on("input keyup paste change", "#input_discount_rate", function () {
            var val = parseInt($(this).val());
            if (val == "" || val == null || isNaN(val)) {
                val = 0;
            }
            if (val > 99) {
                val = 99;
            }
            if ($(this).val() < 0) {
                val = 0;
            }
            $(this).val(val);
            calculate_earn_amount();
        });
        $(document).on("input keyup paste change", "#input_vat_rate", function () {
            var val = parseInt($(this).val());
            if (val == "" || val == null || isNaN(val)) {
                val = 0;
            }
            if (val > 100) {
                val = 100;
            }
            if ($(this).val() < 0) {
                val = 0;
            }
            $(this).val(val);
            calculate_earn_amount();
        });

        function calculate_earn_amount() {
            var input_price = $("#product_price_input").val();
            var discount = 0;
            var vat = 0;
            if ($('#input_discount_rate').val() != "" && $('#input_discount_rate').val() != null) {
                discount = $("#input_discount_rate").val();
            }
            if ($('#input_vat_rate').val() != "" && $('#input_vat_rate').val() != null) {
                vat = $("#input_vat_rate").val();
            }
            input_price = input_price.replace(',', '.');
            var price = parseFloat(input_price);
            commission_rate = parseInt(commission_rate);

            //calculate
            var calculated_amount = 0;
            var vat_amount = 0;
            var earned_amount = 0;
            if (!Number.isNaN(price)) {
                calculated_amount = price - ((price * discount) / 100);
                vat_amount = (calculated_amount * vat) / 100;
                earned_amount = calculated_amount + vat_amount;
                earned_amount = earned_amount - ((earned_amount * commission_rate) / 100);
                earned_amount = earned_amount.toFixed(2);
                calculated_amount = calculated_amount.toFixed(2);
                vat_amount = vat_amount.toFixed(2);
                if (thousands_separator == ',') {
                    calculated_amount = calculated_amount.replace('.', ',');
                    vat_amount = vat_amount.replace('.', ',');
                    earned_amount = earned_amount.replace('.', ',');
                }
            } else {
                calculated_amount = '0' + thousands_separator + '00';
                vat_amount = '0' + thousands_separator + '00';
                earned_amount = '0' + thousands_separator + '00';
            }
            $("#calculated_amount").html(calculated_amount);
            $("#vat_amount").html(vat_amount);
            $("#earned_amount").html(earned_amount);
        }
    </script>
<?php endif; ?>


<script>
    $('#checkbox_discount_rate').change(function () {
        if (!this.checked) {
            $("#discount_input_container").show();
        } else {
            $('#input_discount_rate').val("0");
            $('#input_discount_rate').change();
            $("#discount_input_container").hide();
        }
    });
    $('#checkbox_vat_included').change(function () {
        if (!this.checked) {
            $("#vat_input_container").show();
            $(".calculated_vat_container").show();
        } else {
            $('#input_vat_rate').val("0");
            $('#input_vat_rate').change();
            $("#vat_input_container").hide();
            $(".calculated_vat_container").hide();
        }
    });
</script>
