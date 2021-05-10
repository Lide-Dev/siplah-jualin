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
                            <div class="col-sm-8 col-right">
                                <p> <?= $shop->legal_status ?></p>
                            </div>
                        </div>

                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Tipe Usaha</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->is_umkm ? "UMKM" : "Non UMKM" ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong> <?php echo trans("business_name"); ?></strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->supplier_name ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen NPWP</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->npwp_path ?></p>
                                <p class="m-0">
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#<?php ?>">Lihat Dokumen</button>
                                </p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen NIB</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->nib_path ?></p>
                                <p class="m-0">
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#<?php ?>">Lihat Dokumen</button>
                                </p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Alamat Lengkap</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->full_address ?></p>
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
                                <p> <?= $shop->bank_name ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nomor Rekening</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->bank_account ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Nama Pemilik Rekening</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->bank_account_owner_name ?></p>
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
                        <?php if ($shop->legal_status == "Individu") : ?>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>NIK</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $shop->nik ?></p>
                                </div>
                            </div>

                        <?php else : ?>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Nama Penanggung Jawab</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $shop->responsible_person_name ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Jabatan</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $shop->responsible_person_position ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
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
                                <p> <?= $shop->email ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>No Telepon</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->phone_number ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- END OF DATA ACCOUNT -->
                </div>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>

<!-- MODAL -->
<?php foreach ($payout_requests as $item) :
    $payout = $this->earnings_model->get_user_payout_account($item->user_id);
?>
    <!-- Modal -->
    <div id="accountDetailsModel_<?php echo $item->id; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo trans($item->payout_method); ?></h4>
                </div>
                <div class="modal-body">
                    <?php if (!empty($payout)) : ?>
                        <?php if ($item->payout_method == "paypal") : ?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("user"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <?php $user = get_user($payout->user_id);
                                    if (!empty($user)) : ?>
                                        <strong>
                                            &nbsp;<?php echo $user->username; ?>
                                        </strong>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <?php echo trans("paypal_email_address"); ?>
                                </div>
                                <div class="col-sm-8">
                                    <strong>
                                        &nbsp;<?php echo $payout->payout_paypal_email; ?>
                                    </strong>
                                </div>
                            </div>
                            <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
<?php endforeach; ?>
<!-- END OF MODAL -->

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

    .modal-body .row {
        margin-bottom: 8px;
    }
</style>