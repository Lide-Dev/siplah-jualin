<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "paypal"): ?>

    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $this->payment_settings->paypal_client_id; ?>&currency=<?php echo $currency; ?>"></script>

    <div class="row">
        <div class="col-12">
            <?php $this->load->view('product/_messages'); ?>
        </div>
    </div>
    <div id="payment-button-container" class="paypal-button-cnt">
        <div id="paypal-button-container"></div>
        <div class="col-12 paypal-loader hidden">
            <div class="row">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <strong class="payment-loader-text"><?php echo trans("processing"); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <script>
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '<?php echo get_price($total_amount, 'decimal'); ?>',
                        }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    $('.paypal-loader').show();
                    var data_array = {
                        'payment_id': data.orderID,
                        'currency': '<?php echo $currency; ?>',
                        'payment_amount': '<?php echo get_price($total_amount, 'decimal'); ?>',
                        'payment_status': details.status,
                        'mds_payment_type': '<?php echo $mds_payment_type; ?>',
                        'sys_lang_id': sys_lang_id
                    };
                    data_array[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "paypal-payment-post",
                        data: data_array,
                        success: function (response) {
                            var obj = JSON.parse(response);
                            if (obj.result == 1) {
                                window.location.href = obj.redirect;
                            } else {
                                location.reload();
                            }
                        }
                    });

                });
            }
        }).render('#paypal-button-container');
    </script>
<?php endif; ?>

<style>
    .paypal-loader .spinner {
        margin-bottom: 0 !important;
    }

    .payment-loader-text {
        font-size: 13px;
        font-weight: 600;
    }
</style>
