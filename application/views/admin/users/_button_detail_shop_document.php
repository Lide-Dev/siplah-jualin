<?php
// dd($shop);
$pathname = $filename . "_path";
$id = $filename . "_document"; ?>
<?php if (empty($shop->$pathname)) : ?>
    <button disabled id="<?= $id ?>" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
        Kosong/Tidak Diupload
    </button>
<?php elseif (!$shop->check_file($pathname)) : ?>
    <button disabled id="<?= $id ?>" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
        Dokumen Terhapus
    </button>
<?php else : ?>
    <button id="<?= $id ?>" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_document_viewer">
        Lihat Dokumen
    </button>
<?php endif; ?>