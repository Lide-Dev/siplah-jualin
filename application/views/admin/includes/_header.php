<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo html_escape($title); ?> - <?php echo html_escape($this->general_settings->application_name); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" type="image/png" href="<?php echo get_favicon($this->general_settings); ?>"/>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/icheck/square/purple.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/datatables/jquery.dataTables_themeroller.css">
    <!-- Tags Input -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/tagsinput/jquery.tagsinput.min.css">
    <!-- Bootstrap Toggle -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap-toggle.min.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/pace/pace.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/vendor/magnific-popup/magnific-popup.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/_all-skins.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/custom.css">
    <!-- jQuery 3 -->
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var base_url = '<?php echo base_url(); ?>';
        var csfr_token_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csfr_cookie_name = '<?php echo $this->config->item('csrf_cookie_name'); ?>';
    </script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo admin_url(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b><?php echo html_escape($this->general_settings->application_name); ?></b> <?php echo trans("panel"); ?></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="btn btn-sm btn-success pull-left btn-site-prev" target="_blank" href="<?php echo base_url(); ?>"><i class="fa fa-eye"></i> <?php echo trans("view_site"); ?></a>
                    </li>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo get_user_avatar($this->auth_user); ?>" class="user-image" alt="">
                            <span class="hidden-xs"><?php echo $this->auth_user->username; ?> <i class="fa fa-caret-down"></i> </span>
                        </a>

                        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                            <li>
                                <a href="<?php echo generate_profile_url($this->auth_user->slug); ?>"><i class="fa fa-user"></i> <?php echo trans("profile"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo generate_url("settings"); ?>"><i class="fa fa-cog"></i> <?php echo trans("update_profile"); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo generate_url("settings", "change_password"); ?>"><i class="fa fa-lock"></i> <?php echo trans("change_password"); ?></a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="<?php echo base_url(); ?>logout"><i class="fa fa-sign-out"></i> <?php echo trans("logout"); ?></a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar" style="background-color: #343B4A;">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?php echo get_user_avatar($this->auth_user); ?>" class="img-circle" alt="">
                </div>
                <div class="pull-left info">
                    <p><?php echo $this->auth_user->username; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> <?php echo trans("online"); ?></a>
                </div>
            </div>

            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header"><?php echo trans("navigation"); ?></li>
                <li>
                    <a href="<?php echo admin_url(); ?>">
                        <i class="fa fa-home"></i> <span><?php echo trans("home"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>navigation">
                        <i class="fa fa-th"></i>
                        <span><?php echo trans("navigation"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>slider">
                        <i class="fa fa-sliders"></i>
                        <span><?php echo trans("slider"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span><?php echo trans("orders"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>orders"> <?php echo trans("orders"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>transactions"> <?php echo trans("transactions"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>order-bank-transfers"> <?php echo trans("bank_transfer_notifications"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>invoices"> <?php echo trans("invoices"); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>digital-sales">
                        <i class="fa fa-shopping-bag"></i>
                        <span><?php echo trans("digital_sales"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-basket angle-left" aria-hidden="true"></i>
                        <span><?php echo trans("products"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo generate_url("sell_now"); ?>" target="_blank"> <?php echo trans("add_product"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>products"> <?php echo trans("products"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>pending-products"> <?php echo trans("pending_products"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>hidden-products"> <?php echo trans("hidden_products"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>drafts"> <?php echo trans("drafts"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>deleted-products"> <?php echo trans("deleted_products"); ?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-dollar" aria-hidden="true"></i>
                        <span><?php echo trans("featured_products"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>featured-products"> <?php echo trans("products"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>featured-products-pricing"> <?php echo trans("pricing"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>featured-products-transactions"> <?php echo trans("transactions"); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>quote-requests">
                        <i class="fa fa-tag"></i> <span><?php echo trans("quote_requests"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>categories">
                        <i class="fa fa-folder-open"></i> <span><?php echo trans("categories"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-plus-square-o"></i>
                        <span><?php echo trans("custom_fields"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>add-custom-field"> <?php echo trans("add_custom_field"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>custom-fields"> <?php echo trans("custom_fields"); ?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-money" aria-hidden="true"></i>
                        <span><?php echo trans("earnings"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>earnings"> <?php echo trans("earnings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>seller-balances"> <?php echo trans("seller_balances"); ?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-credit-card" aria-hidden="true"></i>
                        <span><?php echo trans("payouts"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>add-payout"> <?php echo trans("add_payout"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>payout-requests"> <?php echo trans("payout_requests"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>completed-payouts"> <?php echo trans("completed_payouts"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>payout-settings"> <?php echo trans("payout_settings"); ?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file"></i>
                        <span><?php echo trans("pages"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>add-page"> <?php echo trans("add_page"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>pages"> <?php echo trans("pages"); ?></a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file"></i>
                        <span><?php echo trans("blog"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>blog-add-post"> <?php echo trans("add_post"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>blog-posts"> <?php echo trans("posts"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>blog-categories"> <?php echo trans("categories"); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>contact-messages">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        <span><?php echo trans("contact_messages"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-envelope-o"></i> <span><?php echo trans("newsletter"); ?></span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?php echo admin_url(); ?>send-email-subscribers"><?php echo trans("send_email_subscribers"); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo admin_url(); ?>subscribers"><?php echo trans("subscribers"); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span><?php echo trans("users"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>add-administrator"> <?php echo trans("add_administrator"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>administrators"> <?php echo trans("administrators"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>vendors"> <?php echo trans("vendors"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>members"> <?php echo trans("members"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>shop-opening-requests"> <?php echo trans("shop_opening_requests"); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>reviews">
                        <i class="fa fa-star"></i>
                        <span><?php echo trans("reviews"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-comments"></i>
                        <span><?php echo trans("comments"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if ($this->general_settings->comment_approval_system == 1): ?>
                            <li><a href="<?php echo admin_url(); ?>pending-product-comments"> <?php echo trans("product_comments"); ?></a></li>
                            <li><a href="<?php echo admin_url(); ?>pending-blog-comments"> <?php echo trans("blog_comments"); ?></a></li>
                        <?php else: ?>
                            <li><a href="<?php echo admin_url(); ?>product-comments"> <?php echo trans("product_comments"); ?></a></li>
                            <li><a href="<?php echo admin_url(); ?>blog-comments"> <?php echo trans("blog_comments"); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>storage">
                        <i class="fa fa-cloud-upload"></i>
                        <span><?php echo trans("storage"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>cache-system">
                        <i class="fa fa-database"></i>
                        <span><?php echo trans("cache_system"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>seo-tools">
                        <i class="fa fa-wrench"></i> <span><?php echo trans("seo_tools"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>ad-spaces">
                        <i class="fa fa-dollar"></i> <span><?php echo trans("ad_spaces"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-map-marker"></i>
                        <span><?php echo trans("location"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>countries"> <?php echo trans("countries"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>states"> <?php echo trans("states"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>cities"> <?php echo trans("cities"); ?></a></li>
                    </ul>
                </li>

                <li class="header text-uppercase"><?php echo trans("settings"); ?></li>
                <li>
                    <a href="<?php echo admin_url(); ?>preferences">
                        <i class="fa fa-check-square-o"></i>
                        <span><?php echo trans("preferences"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-circle-o"></i>
                        <span><?php echo trans("form_settings"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>form-settings"> <?php echo trans("form_settings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>form-settings/shipping-options"> <?php echo trans("shipping_options"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>form-settings/product-conditions"> <?php echo trans("product_conditions"); ?></a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>payment-settings">
                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i> <span><?php echo trans("payment_settings"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>currency-settings">
                        <i class="fa fa-money"></i> <span><?php echo trans("currency_settings"); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo admin_url(); ?>email-settings">
                        <i class="fa fa-envelope"></i> <span><?php echo trans("email_settings"); ?></span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-paint-brush"></i>
                        <span><?php echo trans("visual_settings"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>visual-settings"> <?php echo trans("visual_settings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>font-settings"> <?php echo trans("font_settings"); ?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cog"></i>
                        <span><?php echo trans("general_settings"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>settings"> <?php echo trans("general_settings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>languages"> <?php echo trans("language_settings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>social-login"> <?php echo trans("social_login"); ?></a></li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i>
                        <span><?php echo trans("system_settings"); ?></span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo admin_url(); ?>system-settings"> <?php echo trans("system_settings"); ?></a></li>
                        <li><a href="<?php echo admin_url(); ?>route-settings"> <?php echo trans("route_settings"); ?></a></li>
                    </ul>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
