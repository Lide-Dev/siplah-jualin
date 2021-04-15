<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $title; ?></h3>
    </div><!-- /.box-header -->

    <div class="box-body">
        <div class="row">
            <!-- include message block -->
            <div class="col-sm-12">
                <?php $this->load->view('admin/includes/_messages'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
</div><!-- /.box-body -->
</div>