<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="nav-breadcrumb" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo trans("profile"); ?></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo $title; ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- DATE PICKER -->
                                <div class="mb-4">
                                    <form action="/action_page.php">
                                        <input type="date" id="select_date" name="select_date">
                                        <input type="date" id="select_date" name="select_date">
                                        <input type="submit" value="Unduh Log">
                                    </form>
                                </div>
                                <!-- DATE PICKER -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" role="grid">
                                        <thead>
                                            <tr role="row">
                                                <th><?php echo trans('name'); ?></th>
                                                <th><?php echo trans('date'); ?></th>
                                                <th><?php echo trans('activity'); ?></th>
                                                <th><?php echo trans('ip'); ?></th>
                                                <th><?php echo trans('browser'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Admin</td>
                                                <td>15 Apr 2021 12:03</td>
                                                <td>Masuk ke akun SIPLah Jualin</td>
                                                <td>IP: 180.243.42.214</td>
                                                <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36</td>
                                            </tr>
                                            <tr>
                                                <td>Admin</td>
                                                <td>15 Apr 2021 12:03</td>
                                                <td>Masuk ke akun SIPLah Jualin</td>
                                                <td>IP: 180.243.42.214</td>
                                                <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36</td>
                                            </tr>
                                            <tr>
                                                <td>Admin</td>
                                                <td>15 Apr 2021 12:03</td>
                                                <td>Masuk ke akun SIPLah Jualin</td>
                                                <td>IP: 180.243.42.214</td>
                                                <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36</td>
                                            </tr>
                                            <tr>
                                                <td>Admin</td>
                                                <td>15 Apr 2021 12:03</td>
                                                <td>Masuk ke akun SIPLah Jualin</td>
                                                <td>IP: 180.243.42.214</td>
                                                <td>Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.114 Safari/537.36</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>