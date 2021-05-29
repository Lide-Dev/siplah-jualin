<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header with-border mb-5">
                    <h3 class="box-title">Detail Buku</h3>
                </div><!-- /.box-header -->

                <div class="box-body">
                    <div class="row" style="margin-bottom: 30px;">
                        <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                            <h4 class="sec-title">Informasi</h4>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Judul</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->title ?></p>
                                </div>
                            </div>

                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>ISBN</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->isbn ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Penerbit</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->publisher_name ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Penulis</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->author ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Tahun Publikasi</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->$publication_year != 0 ? $book->$publication_year : "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Mata Pelajaran</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->subject_name ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Klasifikasi</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->classification_name ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Kelas</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->class_name ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Tingkat Sekolah</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->school_level_name ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Deskripsi</strong>
                                </div>
                                <div class="col-sm-8 col-right">
                                    <p> <?= $book->description ?? "-" ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                            <h4 class="sec-title">Harga</h4>
                            <?php foreach ($book->prices_json as $key => $price_book) : ?>
                                <div class="row row-details">
                                    <div class="col-xs-12 col-sm-4 col-right">
                                        <strong>Zona <?= trim($price_book->zone) ?></strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <p> <?= (!empty($price_book->price) && $price_book->price != 0) ? price_formatted($price_book->price, "IDR") : "-" ?> </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="col-sm-12 col-md-12 col-lg-6 mb-3">
                            <h4 class="sec-title">Spesifikasi</h4>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Tipe Kertas</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->paperType ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Ukuran</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->size ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Berat Belakang Buku</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->backOfBook . " gram" ?? "-" ?></p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Lebar</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->width . " cm" ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Halaman Isi</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->pageContent . " lembar" ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Halaman Cover</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->pageCover . " lembar" ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Berat Cover</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->coverWeight . " gram" ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Bahan Cover</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->coverMaterial ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Cover Terakhir</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->coverFinishing ?? "-" ?> </p>
                                </div>
                            </div>
                            <div class="row row-details">
                                <div class="col-xs-12 col-sm-4 col-right">
                                    <strong>Tehnik Penjilidan</strong>
                                </div>
                                <div class="col-sm-8">
                                    <p> <?= $book->physical_description_json->bindingTechnique ?? "-" ?> </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RESPONSIBLE PERSON -->

                </div><!-- /.box-body -->
            </div>
        </div>
        <div class="col-sm-12 col-left">
            <?php if (!empty($cancel_option) && $cancel_option) :  ?>
                <a href="<?= base_url("cancel-book") ?>" class="btn text-white btn-danger">Batalkan Pilihan</a>
            <?php else : ?>
                <a href="<?= base_url("text_book/select-book/" . $book->id) ?>" class="btn text-white btn-success">Pilih Buku Ini</a>
                <button class="btn btn-custom btn-sell-now" type="button" onclick="javascript:history.back()">Kembali</button>

            <?php endif; ?>
        </div>
    </div>
</div>