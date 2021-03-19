<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row" style="margin-bottom: 15px;">
    <div class="col-sm-12">
        <h3 style="font-size: 18px; font-weight: 600;margin-top: 10px;"><?php echo trans('payment_settings'); ?></h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('paypal'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/paypal_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/paypal.svg" alt="paypal" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_paypal"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_enabled" value="1" id="paypal_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="paypal_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_enabled" value="0" id="paypal_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="paypal_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("mode"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_mode" value="live" id="paypal_mode_1"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_mode == 'live') ? 'checked' : ''; ?>>
                            <label for="paypal_mode_1" class="option-label"><?php echo trans("production"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="paypal_mode" value="sandbox" id="paypal_mode_2"
                                   class="square-purple" <?php echo ($this->payment_settings->paypal_mode == 'sandbox') ? 'checked' : ''; ?>>
                            <label for="paypal_mode_2" class="option-label"><?php echo trans("sandbox"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('client_id'); ?></label>
                    <input type="text" class="form-control" name="paypal_client_id" placeholder="<?php echo trans('client_id'); ?>"
                           value="<?php echo $this->payment_settings->paypal_client_id; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="paypal_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->paypal_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Midtrans</h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/midtrans_settings_post'); ?>
            <div class="box-body">
                <img src="<?php echo base_url(); ?>assets/img/payment/midtrans.png" height="100" alt="midtrans" class="img-payment-logo">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_midtrans"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="midtrans_enabled" value="1" id="midtrans_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->midtrans_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="midtrans_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="midtrans_enabled" value="0" id="midtrans_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->midtrans_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="midtrans_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("mode"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="midtrans_mode" value="production" id="midtrans_mode_1"
                                   class="square-purple" <?php echo ($this->payment_settings->midtrans_mode == 'production') ? 'checked' : ''; ?>>
                            <label for="midtrans_mode_1" class="option-label"><?php echo trans("production"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="midtrans_mode" value="sandbox" id="midtrans_mode_2"
                                   class="square-purple" <?php echo ($this->payment_settings->midtrans_mode == 'sandbox') ? 'checked' : ''; ?>>
                            <label for="midtrans_mode_2" class="option-label"><?php echo trans("sandbox"); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Merchant ID</label>
                    <input type="text" class="form-control" name="midtrans_merchant_id" placeholder="Merchant ID"
                           value="<?php echo $this->payment_settings->midtrans_merchant_id; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('client_id'); ?></label>
                    <input type="text" class="form-control" name="midtrans_client_id" placeholder="<?php echo trans('client_id'); ?>"
                           value="<?php echo $this->payment_settings->midtrans_client_id; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('secret_key'); ?></label>
                    <input type="text" class="form-control" name="midtrans_secret_key" placeholder="<?php echo trans('secret_key'); ?>"
                           value="<?php echo $this->payment_settings->midtrans_secret_key; ?>" <?php echo ($this->rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('bank_transfer'); ?></h3><br>
                <small><?php echo trans("bank_transfer_exp"); ?></small>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/bank_transfer_settings_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_bank_transfer"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="bank_transfer_enabled" value="1" id="bank_transfer_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->bank_transfer_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="bank_transfer_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="bank_transfer_enabled" value="0" id="bank_transfer_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->bank_transfer_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="bank_transfer_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('bank_accounts'); ?></label>
                    <textarea id="ckEditorBankAccounts" class="form-control" name="bank_transfer_accounts"><?php echo $this->payment_settings->bank_transfer_accounts; ?></textarea>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('cash_on_delivery'); ?></h3><br>
                <small><?php echo trans("cash_on_delivery_exp"); ?></small>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/cash_on_delivery_settings_post'); ?>
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("mes_cash_on_delivery"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans("status"); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="cash_on_delivery_enabled" value="1" id="cash_on_delivery_enabled_1"
                                   class="square-purple" <?php echo ($this->payment_settings->cash_on_delivery_enabled == 1) ? 'checked' : ''; ?>>
                            <label for="cash_on_delivery_enabled_1" class="option-label"><?php echo trans('enable'); ?></label>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="cash_on_delivery_enabled" value="0" id="cash_on_delivery_enabled_2"
                                   class="square-purple" <?php echo ($this->payment_settings->cash_on_delivery_enabled != 1) ? 'checked' : ''; ?>>
                            <label for="cash_on_delivery_enabled_2" class="option-label"><?php echo trans('disable'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>

<style>
    .img-payment-logo {
        height: 28px;
        position: absolute;
        right: 15px;
        top: 15px;
    }
</style>

<script>
    $('input[name=iyzico_type]').on('ifChecked', function (event) {
        var value = $(this).val();
        if (value == "marketplace") {
            $("#form_submerchant").show();
        } else {
            $("#form_submerchant").hide();
        }
    });
</script>
