<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("detail_shop"); ?></h3>
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <!-- PPROFILE business -->
                        <h4 class="sec-title"><?php echo trans("profile_business"); ?></h4>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong> <?php echo trans("business_type"); ?></strong>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-right">
                                <p> <?= $shop->businesstype ?></p>
                            </div>
                        </div>

                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Tipe Usaha</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong> <?php echo trans("business_name"); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen NPWP</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen NIB</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Alamat Lengkap</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Titik Lokasi</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <!-- END OF PROFILE business -->
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <h4 class="sec-title">Rekening Bank</h4>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nama BANK</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nomor Rekening</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nama Pemilik Rekening</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12">
                                <div class="table-orders-user">
                                    <a href="" target="_blank">
                                        <img src="" alt="" class="img-responsive" style="height: 120px;">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- RESPONSIBLE PERSON -->
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <h4 class="sec-title">Penanggung Jawab</h4>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nama Penanggung Jawab</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Jabatan</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                    </div>
                    <!-- END OF RESPONSIBLE PERSON -->
                    <!-- DATA ACCOUNT -->
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <h4 class="sec-title">Akun</h4>

                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Email</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>No Telepone</strong>
                            </div>
                            <div class="col-sm-8">
                                <strong class="font-right"></strong>
                            </div>
                        </div>
                    </div>
                    <!-- END OF DATA ACCOUNT -->
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>

<style>
    .sec-title {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        font-weight: 600;
    }

    .font-right {
        font-weight: 600;
        margin-left: 5px;
    }

    .font-right a {
        color: #55606e;
    }

    .row-details {
        margin-bottom: 10px;
    }

    .col-right {
        max-width: 240px;
    }

    .label {
        font-size: 12px !important;
    }

    @media (max-width: 768px) {
        .col-right {
            width: 100%;
            max-width: none;
        }

        .col-sm-8 strong {
            margin-left: 0;
        }
    }
</style>