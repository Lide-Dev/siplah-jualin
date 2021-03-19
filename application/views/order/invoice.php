<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $this->selected_lang->short_form ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo xss_clean($title); ?> - <?php echo xss_clean($this->settings->site_title); ?></title>
    <meta name="description" content="<?php echo xss_clean($description); ?>"/>
    <meta name="keywords" content="<?php echo xss_clean($keywords); ?>"/>
    <meta name="author" content="Codingest"/>
    <link rel="shortcut icon" type="image/png" href="<?php echo get_favicon($this->general_settings); ?>"/>
    <meta property="og:locale" content="en-US"/>
    <meta property="og:site_name" content="<?php echo xss_clean($this->general_settings->application_name); ?>"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css"/>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="container-invoice">
                <div id="content" class="card">
                    <div class="card-body invoice p-0">
                        <div class="row" style="padding: 45px 30px;">
                            <div class="col-md-6">
                                <div class="logo">
                                    <img src="<?php echo lang_base_url(); ?>assets/img/invoicelogo.png">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="float-right">
                                    <?php $show_all_products = true;
                                    $prefix = "";
                                    if ($this->auth_user->role != "admin" && $order->buyer_id != $this->auth_user->id) {
                                        $show_all_products = false;
                                        $prefix = "VN";
                                    } ?>
                                    <p class="font-weight-bold mb-1"><span style="display: inline-block;width: 100px;"><?php echo trans("invoice"); ?>:</span>#<?php echo $prefix . $order->order_number; ?></p>
                                    <p class="font-weight-bold"><span style="display: inline-block;width: 100px;"><?php echo trans("date"); ?>:</span><?php echo helper_date_format($order->created_at); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding: 45px 30px;">
                            <div class="col-md-6">
                                <p class="font-weight-bold mb-3"><?php echo trans("client_information"); ?></p>
                                <?php if (!empty($invoice->client_username)): ?>
                                    <p class="mb-1"><?php echo html_escape($invoice->client_username); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($invoice->client_first_name) || !empty($invoice->client_last_name)): ?>
                                    <p class="mb-1"><?php echo html_escape($invoice->client_first_name); ?>&nbsp;<?php echo html_escape($invoice->client_last_name); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($invoice->client_address)): ?>
                                    <p class="mb-1"><?php echo html_escape($invoice->client_address); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="float-right">
                                    <p class="font-weight-bold mb-3"><?php echo trans("payment_details"); ?></p>
                                    <p class="mb-1"><span style="display: inline-block;min-width: 158px;"><?php echo trans("payment_method"); ?>:</span><?php echo $order->payment_method; ?></p>
                                    <p class="mb-1"><span style="display: inline-block;min-width: 158px;"><?php echo trans("currency"); ?>:</span><?php echo $order->price_currency; ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="row p-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th class="border-0 font-weight-bold"><?php echo trans("seller"); ?></th>
                                            <th class="border-0 font-weight-bold"><?php echo trans("product_id"); ?></th>
                                            <th class="border-0 font-weight-bold"><?php echo trans("description"); ?></th>
                                            <th class="border-0 font-weight-bold"><?php echo trans("quantity"); ?></th>
                                            <th class="border-0 font-weight-bold"><?php echo trans("unit_price"); ?></th>
                                            <?php if ($this->general_settings->vat_status): ?>
                                                <th class="border-0 font-weight-bold"><?php echo trans("vat"); ?></th>
                                            <?php endif; ?>
                                            <th class="border-0 font-weight-bold"><?php echo trans("shipping"); ?></th>
                                            <th class="border-0 font-weight-bold"><?php echo trans("total"); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if ($show_all_products) {
                                            $sale_subtotal = $order->price_subtotal;
                                            $sale_vat = $order->price_vat;
                                            $sale_shipping = $order->price_shipping;
                                            $sale_total = $order->price_total;
                                        } else {
                                            $sale_subtotal = 0;
                                            $sale_vat = 0;
                                            $sale_shipping = 0;
                                            $sale_total = 0;
                                        }

                                        $is_order_has_physical_product = false;
                                        if (!empty($invoice_items) && is_array($invoice_items)):
                                            foreach ($invoice_items as $item):
                                                $order_product_id = @$item['id'];
                                                $seller = @$item['seller'];
                                                if (!empty($order_product_id)):
                                                    $order_product = $this->order_model->get_order_product($order_product_id);
                                                    if (!empty($order_product)):
                                                        if ($order_product->product_type == 'physical') {
                                                            $is_order_has_physical_product = true;
                                                        }
                                                        if ($order_product->buyer_id == $this->auth_user->id || $order_product->seller_id == $this->auth_user->id || $this->auth_user->role == "admin"):
                                                            if ($show_all_products == false):
                                                                $sale_subtotal += $order_product->product_unit_price * $order_product->product_quantity;
                                                                $sale_vat += $order_product->product_vat;
                                                                $sale_shipping += $order_product->product_shipping_cost;
                                                                $sale_total += $order_product->product_total_price;
                                                            endif; ?>
                                                            <tr style="font-size: 15px;">
                                                                <td><?php echo html_escape($seller); ?></td>
                                                                <td><?php echo $order_product->product_id; ?></td>
                                                                <td><?php echo $order_product->product_title; ?></td>
                                                                <td><?php echo $order_product->product_quantity; ?></td>
                                                                <td><?php echo price_formatted($order_product->product_unit_price, $order_product->product_currency); ?></td>
                                                                <?php if ($this->general_settings->vat_status): ?>
                                                                    <td>
                                                                        <?php if (!empty($order_product->product_vat)): ?>
                                                                            <?php echo price_formatted($order_product->product_vat, $order_product->product_currency); ?>&nbsp;(<?php echo $order_product->product_vat_rate; ?>%)
                                                                        <?php endif; ?>
                                                                    </td>
                                                                <?php endif; ?>
                                                                <td><?php echo price_formatted($order_product->product_shipping_cost, $order_product->product_currency); ?></td>
                                                                <td><?php echo price_formatted($order_product->product_total_price, $order_product->product_currency); ?></td>
                                                            </tr>
                                                        <?php endif;
                                                    endif;
                                                endif;
                                            endforeach;
                                        endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="order-total float-right">
                                    <div class="row mb-2">
                                        <div class="col-6 col-left">
                                            <?php echo trans("subtotal"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo price_formatted($sale_subtotal, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                    <?php if (!empty($sale_vat)): ?>
                                        <div class="row mb-2">
                                            <div class="col-6 col-left">
                                                <?php echo trans("vat"); ?>
                                            </div>
                                            <div class="col-6 col-right">
                                                <strong class="font-600"><?php echo price_formatted($sale_vat, $order->price_currency); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($is_order_has_physical_product): ?>
                                        <div class="row mb-2">
                                            <div class="col-6 col-left">
                                                <?php echo trans("shipping"); ?>
                                            </div>
                                            <div class="col-6 col-right">
                                                <strong class="font-600"><?php echo price_formatted($sale_shipping, $order->price_currency); ?></strong>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="row mb-2">
                                        <div class="col-6 col-left">
                                            <?php echo trans("total"); ?>
                                        </div>
                                        <div class="col-6 col-right">
                                            <strong class="font-600"><?php echo price_formatted($sale_total, $order->price_currency); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        body {
                            font-size: 16px !important;
                        }

                        .container-invoice {
                            max-width: 900px;
                            margin: 0 auto;
                        }

                        table {
                            border-bottom: 1px solid #dee2e6;
                        }

                        table th {
                            font-size: 14px;
                            white-space: nowrap;
                        }

                        .order-total {
                            width: 400px;
                            max-width: 100%;
                            float: right;
                            padding: 20px;
                        }

                        .order-total .col-left {
                            font-weight: 600;
                        }

                        .order-total .col-right {
                            text-align: right;
                        }

                        #btn_print {
                            min-width: 180px;
                        }

                        @media print {
                            .hidden-print {
                                display: none !important;
                            }
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-12 text-center mt-3">
            <button id="btn_print" class="btn btn-secondary btn-md hidden-print">
                <svg id="i-print" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="16" height="16" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" style="margin-top: -4px;">
                    <path d="M7 25 L2 25 2 9 30 9 30 25 25 25 M7 19 L7 30 25 30 25 19 Z M25 9 L25 2 7 2 7 9 M22 14 L25 14"/>
                </svg>
                &nbsp;&nbsp;<?php echo trans("print"); ?></button>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script>
    $(document).on('click', '#btn_print', function () {
        window.print();
    });
</script>
</body>
</html>

