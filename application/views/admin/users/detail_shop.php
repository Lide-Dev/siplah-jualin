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
                                <strong>Profil Usaha</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->is_business_entity == 1 ? "Badan Usaha" : "Individu" ?></p>
                            </div>
                        </div>

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
                                <p> <?= $shop->business_type ?></p>
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
                                <button <?= $shop->check_file("npwp_path") ? "" : "disabled" ?> id="npwp_document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
                                    <?= $shop->check_file("npwp_path") ? "Lihat Dokumen" : "Dokumen Terhapus" ?>
                                </button>

                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen NIB</strong>
                            </div>
                            <div class="col-sm-8">
                                <button <?= $shop->check_file("nib_path") ? "" : "disabled" ?> id="nib_document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
                                    <?= $shop->check_file("nib_path") ? "Lihat Dokumen" : "Dokumen Terhapus" ?>
                                </button>

                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Provinsi</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->province_name ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Kabupaten/Kota</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->city_name ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Kecamatan</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->district ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Desa</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->village ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Alamat</strong>
                            </div>
                            <div class="col-sm-8">
                                <p> <?= $shop->address ?></p>
                            </div>
                        </div>
                        <div class="row row-details">
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Titik Lokasi</strong>
                            </div>
                            <div class="col-sm-8">
                                <div class="map-container">
                                    <iframe src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?= $shop->full_address ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true" id="IframeMap" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                                </div>
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
                            <div class="col-xs-12 col-sm-4 col-right">
                                <strong>Dokumen Buku Rekening</strong>
                            </div>
                            <div class="col-sm-8">
                                <button <?= $shop->check_file("cover_book_path") ? "" : "disabled" ?> id="cover_book_document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
                                    <?= $shop->check_file("cover_book_path") ? "Lihat Dokumen" : "Dokumen Terhapus" ?>
                                </button>
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
                        <?php if ($shop->is_business_entity == 0) : ?>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>NIK</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $shop->nik ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Dokumen NIK</strong>
                                </div>
                                <div class="col-sm-8">
                                    <button <?= $shop->check_file("ktp_path") ? "" : "disabled" ?> id="ktp_document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
                                        <?= $shop->check_file("ktp_path") ? "Lihat Dokumen" : "Dokumen Terhapus" ?>
                                    </button>
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
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Dokumen SIUP</strong>
                                </div>
                                <div class="col-sm-8">
                                    <button <?= $shop->check_file("siup_path") ? "" : "disabled" ?> id="siup_document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
                                        <?= $shop->check_file("siup_path") ? "Lihat Dokumen" : "Dokumen Terhapus" ?>
                                    </button>
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

<div class="modal fade" id="modal_document_viewer" tabindex="-1" role="dialog" aria-labelledby="modal_center_document_viewer" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title_document_viewer">Dokumen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="document_viewer_body" class="modal-body container">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>
    var files = {
        npwp_document: {
            ext: "<?= $shop->npwp_ext ?? "" ?>",
            url: "<?= base_url("uploads/supplier_document/{$shop->npwp_path}") ?>",
            alt: "NPWP Dokumen"
        },
        nib_document: {
            ext: "<?= $shop->nib_ext ?? "" ?>",
            url: "<?= base_url("uploads/supplier_document/{$shop->nib_path}") ?>",
            alt: "NIB Dokumen"
        },
        siup_document: {
            ext: "<?= $shop->siup_ext ?? "" ?>",
            url: "<?= base_url("uploads/supplier_document/{$shop->siup_path}") ?>",
            alt: "SIUP Dokumen"
        },
        ktp_document: {
            ext: "<?= $shop->ktp_ext ?? "" ?>",
            url: "<?= base_url("uploads/supplier_document/{$shop->ktp_path}") ?>",
            alt: "KTP Dokumen"
        }
    }

    $("#npwp_document").click(function() {
        console.log("t1");
        document_viewer("npwp_document")
    });
    $("#nib_document").click(function() {
        console.log("t2");
        document_viewer("nib_document")
    });
    $("#siup_document").click(function() {
        console.log("t3");
        document_viewer("siup_document")
    });
    $("#ktp_document").click(function() {
        console.log("t4");
        document_viewer("ktp_document")
    });

    function document_viewer(type) {
        if (files[type].ext != "pdf") {
            $("#document_viewer_body").html(
                `<img src="${files[type].url}" class="img-doc" alt="${files[type].alt}">`
            )
        } else {
            $("#document_viewer_body").html(
                `<iframe frameborder="0" scrolling="no" class="prev-pdf" width="100%" height="480" src="https://docs.google.com/gview?url=${files[type].url}&embedded=true"></iframe>`
            )
        }
    }
</script>


<style>
    .pdf-viewer {
        max-width: 500px;
        width: 100%;
        height: 480px;
    }

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

    .img-doc {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        max-width: 540px;
        height: auto;
    }

    .prev-pdf {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        max-width: 540px;
        height: auto;
    }
</style>